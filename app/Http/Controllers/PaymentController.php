<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Generate Order ID dengan format OMZ
     */
    private function generateOrderId($tenantId)
    {
        return 'OMZ-' . $tenantId . '-' . time() . '-' . strtoupper(Str::random(6));
    }

    /**
     * ✅ FIX 1: Cek apakah tenant sudah bayar dalam 30 hari terakhir
     * Return record pembayaran kalau sudah ada, null kalau belum
     */
    private function getPembayaranAktif($tenantId)
    {
        return Pembayaran::where('tenant_id', $tenantId)
            ->where('status', 'confirmed')
            ->where('metode', '!=', 'qris_manual') // exclude QRIS manual
            ->where('tanggal_konfirmasi', '>=', now()->subDays(30))
            ->latest('tanggal_konfirmasi')
            ->first();
    }

    // Halaman Checkout (sebelum bayar)
    public function checkout($planId)
    {
        $plan = Plan::findOrFail($planId);
        $tenantId = session('pending_tenant_id');

        if (!$tenantId && auth()->check()) {
            $tenant = auth()->user()->tenant;
            if ($tenant) {
                $tenantId = $tenant->id_tenant;
                session(['pending_tenant_id' => $tenantId]);
            }
        }

        if (!$tenantId) {
            return redirect()->route('register')
                ->with('error', 'Tidak ada pembayaran yang perlu diselesaikan.');
        }

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return redirect()->route('register')->with('error', 'Tenant tidak ditemukan.');
        }

        // ✅ FIX 2: Cek apakah sudah bayar & masih aktif
        $sudahBayar = $tenant->status_langganan === 'active';
        $pembayaranAktif = $this->getPembayaranAktif($tenantId);

        if ($pembayaranAktif && $sudahBayar && $tenant->tanggal_berakhir && $tenant->tanggal_berakhir > now()) {
            \Log::info('Checkout blocked - Already paid', [
                'tenant_id' => $tenantId,
                'last_order_id' => $pembayaranAktif->order_id,
                'paid_at' => $pembayaranAktif->tanggal_konfirmasi,
                'status' => $tenant->status_langganan,
            ]);

            return redirect()->route('status.langganan')
                ->with('success', 'Pembayaran Anda sudah selesai. Akun aktif sampai '
                    . $tenant->tanggal_berakhir->format('d M Y'));
        }

        // ✅ FIX 2b: Cek apakah status butuh pembayaran
        $statusButuhBayar = in_array($tenant->status_langganan, ['pending', 'expired', 'trial', 'suspended']);
        $isUpgrade = $tenant->plan_id != $planId;
        $isPerpanjang = $tenant->plan_id == $planId;

        if (!$statusButuhBayar && !$isUpgrade && !$isPerpanjang) {
            \Log::info('Checkout blocked - No payment needed', [
                'tenant_id' => $tenantId,
                'status' => $tenant->status_langganan,
                'is_upgrade' => $isUpgrade,
            ]);

            return redirect()->route('status.langganan')
                ->with('info', 'Pembayaran Anda sudah selesai. Tidak perlu checkout lagi.');
        }

        // ✅ FIX 3: Cek existing payment — filter by metode midtrans
        // ✅ FIX 3: Cek existing payment — filter semua metode
        $existingPayment = Pembayaran::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->whereIn('metode', ['midtrans', 'qris_manual'])
            ->latest()
            ->first();

        if ($existingPayment && $existingPayment->order_id) {
            $orderId = $existingPayment->order_id;
        } else {
            // ✅ Jangan bikin record di sini — record dibuat saat user pilih metode
            $orderId = null;
        }

        session([
            'upgrade_plan_id' => $planId,
            'pending_order_id' => $orderId,
        ]);

        $existingToken = session('pending_snap_token');

        return view('payment.checkout', compact('plan', 'tenant', 'existingToken', 'orderId'));
    }

    // Generate Snap Token
    public function pay(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $tenantId = session('pending_tenant_id');

        if (!$tenantId) {
            return redirect()->route('register')->with('error', 'Sesi pendaftaran habis.');
        }

        $tenant = Tenant::findOrFail($tenantId);

        // ✅ FIX 4: Cek sudah bayar dalam 30 hari terakhir
        $pembayaranAktif = $this->getPembayaranAktif($tenantId);

        if ($pembayaranAktif) {
            \Log::info('Pay blocked - Already paid', [
                'tenant_id' => $tenantId,
                'last_order_id' => $pembayaranAktif->order_id,
            ]);

            return redirect()->route('status.langganan')
                ->with('success', 'Pembayaran Anda sudah selesai.');
        }

        // ✅ FIX 5: Batalkan SEMUA pending SEBELUM bikin baru
        $cancelledPayments = Pembayaran::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->where('metode', 'midtrans')
            ->get();

        foreach ($cancelledPayments as $cancelled) {
            $cancelled->update([
                'status' => 'cancelled',
                'keterangan' => 'Dibatalkan - Order baru dibuat. Order lama: ' . $cancelled->order_id,
            ]);

            \Log::info('Pending payment cancelled:', [
                'order_id' => $cancelled->order_id,
                'tenant_id' => $tenantId,
            ]);
        }

        // Cek apakah ini permintaan "Ganti Metode"
        $isNewPayment = $request->input('new_payment', false) || $request->input('ganti_metode', false);

        $orderId = session('pending_order_id')
            ?? $request->order_id
            ?? $request->input('order_id')
            ?? null;

        if ($isNewPayment) {
            session()->forget('pending_snap_token');
            $orderId = $this->generateOrderId($tenantId);
            session(['pending_order_id' => $orderId]);

            \Log::info('Ganti Metode - Membuat order_id baru:', [
                'new_order_id' => $orderId,
                'tenant_id' => $tenantId,
            ]);
        }

        if (!$orderId) {
            $orderId = $this->generateOrderId($tenantId);
        }

        \Log::info('Pay Method - Order ID:', [
            'order_id' => $orderId,
            'is_new_payment' => $isNewPayment,
            'tenant_id' => $tenantId
        ]);

        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'tenant_id' => $tenantId,
                'plan_id' => $plan->id,
                'order_id' => $orderId,
                'jumlah' => $plan->harga,
                'status' => 'pending',
                'metode' => 'midtrans',
                'keterangan' => 'Menunggu pembayaran - ' . $plan->nama_paket,
            ]);
        } else {
            $pembayaran->update([
                'plan_id' => $plan->id,
                'jumlah' => $plan->harga,
                'status' => 'pending',
                'keterangan' => 'Menunggu pembayaran - ' . $plan->nama_paket,
            ]);
        }

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $plan->harga,
            ],
            'item_details' => [
                [
                    'id' => $plan->id,
                    'price' => (int) $plan->harga,
                    'quantity' => 1,
                    'name' => 'Langganan ' . $plan->nama_paket,
                ],
            ],
            'customer_details' => [
                'first_name' => $tenant->nama_pemilik,
                'email' => $tenant->email,
                'phone' => $tenant->no_hp ?? '',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            session([
                'pending_order_id' => $orderId,
                'pending_snap_token' => $snapToken,
                'upgrade_plan_id' => $plan->id,
            ]);

            return view('payment.pay', compact('snapToken', 'plan', 'tenant', 'orderId'));
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'order_id sudah digunakan')) {
                Pembayaran::where('tenant_id', $tenantId)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'cancelled',
                        'keterangan' => 'Dibatalkan - Order ID sudah digunakan di Midtrans',
                    ]);

                $newOrderId = $this->generateOrderId($tenantId);
                session(['pending_order_id' => $newOrderId]);

                Pembayaran::create([
                    'tenant_id' => $tenantId,
                    'plan_id' => $plan->id,
                    'order_id' => $newOrderId,
                    'jumlah' => $plan->harga,
                    'status' => 'pending',
                    'metode' => 'midtrans',
                    'keterangan' => 'Menunggu pembayaran - ' . $plan->nama_paket,
                ]);

                $params['transaction_details']['order_id'] = $newOrderId;

                try {
                    $snapToken = Snap::getSnapToken($params);

                    session([
                        'pending_order_id' => $newOrderId,
                        'pending_snap_token' => $snapToken,
                        'upgrade_plan_id' => $plan->id,
                    ]);

                    return view('payment.pay', compact('snapToken', 'plan', 'tenant', 'orderId'));
                } catch (\Exception $e2) {
                    return back()->with('error', 'Gagal membuat transaksi: ' . $e2->getMessage());
                }
            }

            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function payAgain()
    {
        $tenant = auth()->user()->tenant;

        if (!$tenant || $tenant->status_langganan === 'active') {
            return redirect()->route('dashboard');
        }

        $plan = $tenant->plan;

        if (!$plan) {
            return back()->with('error', 'Paket langganan tidak ditemukan.');
        }

        // ✅ FIX 6: Cek sudah bayar dalam 30 hari terakhir
        $pembayaranAktif = $this->getPembayaranAktif($tenant->id_tenant);

        if ($pembayaranAktif) {
            return redirect()->route('status.langganan')
                ->with('success', 'Pembayaran Anda sudah selesai.');
        }

        // Batalkan pending
        $cancelledPayments = Pembayaran::where('tenant_id', $tenant->id_tenant)
            ->where('status', 'pending')
            ->where('metode', 'midtrans')
            ->get();

        foreach ($cancelledPayments as $cancelled) {
            $cancelled->update([
                'status' => 'cancelled',
                'keterangan' => 'Dibatalkan - Pembayaran ulang. Order lama: ' . $cancelled->order_id,
            ]);
        }

        $orderId = $this->generateOrderId($tenant->id_tenant);

        Pembayaran::create([
            'tenant_id' => $tenant->id_tenant,
            'plan_id' => $plan->id,
            'order_id' => $orderId,
            'jumlah' => $plan->harga,
            'status' => 'pending',
            'metode' => 'midtrans',
            'keterangan' => 'Pembayaran ulang - ' . $plan->nama_paket,
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $plan->harga,
            ],
            'item_details' => [
                [
                    'id' => $plan->id,
                    'price' => (int) $plan->harga,
                    'quantity' => 1,
                    'name' => 'Langganan ' . $plan->nama_paket,
                ],
            ],
            'customer_details' => [
                'first_name' => $tenant->nama_pemilik,
                'email' => $tenant->email,
                'phone' => $tenant->no_hp ?? '',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            session([
                'pending_order_id' => $orderId,
                'pending_snap_token' => $snapToken,
                'upgrade_plan_id' => $plan->id,
            ]);

            return view('payment.pay', compact('snapToken', 'plan', 'tenant', 'orderId'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * NOTIFICATION - Update dengan transaction_id
     */
    public function notification(Request $request)
    {
        try {
            $payload = $request->all();

            \Log::info('Midtrans Notification:', $payload);

            // ============================================
            // ✅ 1. AMBIL DATA DARI PAYLOAD
            // ============================================
            $orderId = $payload['order_id'] ?? null;
            $transactionId = $payload['transaction_id'] ?? $orderId;
            $statusCode = $payload['status_code'] ?? null;
            $grossAmount = $payload['gross_amount'] ?? null;
            $signatureKey = $payload['signature_key'] ?? null;
            $transactionStatus = $payload['transaction_status'] ?? null;
            $paymentType = $payload['payment_type'] ?? null;
            $fraudStatus = $payload['fraud_status'] ?? null;
            $merchantId = $payload['merchant_id'] ?? null;
            $vaNumber = $payload['va_numbers'][0]['va_number'] ?? null;
            $bank = $payload['va_numbers'][0]['bank'] ?? $payload['bank'] ?? null;

            // ============================================
            // ✅ 2. VALIDASI ORDER ID
            // ============================================
            if (!$orderId) {
                \Log::warning('Notification missing order_id', $payload);
                return response()->json(['status' => 'invalid payload'], 400);
            }

            // ============================================
            // ✅ 3. VERIFIKASI MERCHANT ID
            // ============================================
            $expectedMerchantId = config('midtrans.merchant_id');
            if ($expectedMerchantId && $merchantId && $merchantId !== $expectedMerchantId) {
                \Log::warning('Invalid merchant_id', [
                    'order_id' => $orderId,
                    'received' => $merchantId,
                    'expected' => $expectedMerchantId,
                ]);
                return response()->json(['status' => 'invalid merchant'], 403);
            }

            // ============================================
            // ✅ 4. VERIFIKASI SIGNATURE
            // ============================================
            if ($signatureKey && $statusCode && $grossAmount) {
                $serverKey = config('midtrans.server_key');
                $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

                if (!hash_equals($expectedSignature, $signatureKey)) {
                    \Log::warning('Invalid signature', [
                        'order_id' => $orderId,
                        'status_code' => $statusCode,
                        'gross_amount' => $grossAmount,
                    ]);
                    return response()->json(['status' => 'invalid signature'], 403);
                }
            }

            // ============================================
            // ✅ 5. CARI PEMBAYARAN
            // ============================================
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                \Log::warning('Payment not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'payment not found'], 404);
            }

            // ============================================
            // ✅ 6. IDEMPOTENT — CEK DUPLIKAT TRANSAKSI
            // ============================================
            // Kalau sudah confirmed & transaction_id sama → skip proses
            if (
                $pembayaran->status === 'confirmed'
                && $pembayaran->transaction_id
                && $pembayaran->transaction_id === $transactionId
            ) {
                \Log::info('Notification already processed, skip:', [
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                ]);
                return response()->json(['status' => 'already processed']);
            }

            $tenant = Tenant::find($pembayaran->tenant_id);

            // ============================================
            // ✅ 7. HANDLE BERDASARKAN STATUS
            // ============================================
            if (in_array($transactionStatus, ['settlement', 'capture'])) {

                // ✅ Cek fraud_status untuk kartu kredit
                if ($fraudStatus === 'deny') {
                    \Log::warning('Payment denied by fraud detection', [
                        'order_id' => $orderId,
                        'fraud_status' => $fraudStatus,
                    ]);

                    $pembayaran->update([
                        'transaction_id' => $transactionId,
                        'status' => 'cancelled',
                        'metode' => $paymentType,
                        'keterangan' => 'Ditolak oleh fraud detection | Order: ' . $orderId,
                    ]);

                    return response()->json(['status' => 'success']);
                }

                // ============================================
                // ✅ Update Tenant (skip kalau sudah aktif)
                // ============================================
                if ($tenant) {
                    $newPlanId = $pembayaran->plan_id
                        ?? session('upgrade_plan_id')
                        ?? $this->getPlanIdFromAmount($grossAmount);

                    $sudahAktif = $tenant->status_langganan === 'active'
                        && $tenant->plan_id == $newPlanId
                        && $tenant->tanggal_berakhir
                        && $tenant->tanggal_berakhir > now()->addDays(25);

                    if ($sudahAktif) {
                        \Log::info('Tenant sudah aktif, skip update:', [
                            'tenant_id' => $tenant->id_tenant,
                            'order_id' => $orderId,
                        ]);
                    } else {
                        $tenant->update([
                            'status_langganan' => 'active',
                            'plan_id' => $newPlanId ?? $tenant->plan_id,
                            'tanggal_berakhir' => now()->addDays(30),
                            'max_user' => $newPlanId ? Plan::find($newPlanId)?->max_user ?? 10 : $tenant->max_user,
                        ]);

                        \Log::info('Tenant activated via notification:', [
                            'tenant_id' => $tenant->id_tenant,
                            'order_id' => $orderId,
                            'plan_id' => $newPlanId,
                            'expired_at' => now()->addDays(30),
                        ]);
                    }
                }

                // ============================================
                // ✅ Update Pembayaran
                // ============================================
                $keterangan = 'Pembayaran berhasil';
                if ($bank) {
                    $keterangan .= ' via ' . strtoupper($bank);
                } elseif ($paymentType) {
                    $keterangan .= ' via ' . strtoupper($paymentType);
                }
                if ($vaNumber) {
                    $keterangan .= ' - VA: ' . $vaNumber;
                }
                $keterangan .= ' | Order: ' . $orderId;

                $pembayaran->update([
                    'transaction_id' => $transactionId,
                    'status' => 'confirmed',
                    'metode' => $paymentType,
                    'keterangan' => $keterangan,
                    'tanggal_bayar' => now(),
                    'tanggal_konfirmasi' => now(),
                ]);

                session()->forget([
                    'pending_tenant_id',
                    'pending_order_id',
                    'upgrade_plan_id',
                    'pending_snap_token'
                ]);

            } elseif ($transactionStatus === 'pending') {
                // ============================================
                // ✅ PENDING
                // ============================================
                $pembayaran->update([
                    'transaction_id' => $transactionId,
                    'status' => 'pending',
                    'metode' => $paymentType,
                    'keterangan' => 'Menunggu pembayaran - ' . strtoupper($paymentType ?? 'Midtrans') . ' | Order: ' . $orderId,
                ]);

            } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny', 'failure'])) {
                // ============================================
                // ✅ FAILED / EXPIRED / CANCELLED
                // ============================================
                $pembayaran->update([
                    'transaction_id' => $transactionId,
                    'status' => $transactionStatus === 'expire' ? 'expired' : 'cancelled',
                    'metode' => $paymentType,
                    'keterangan' => 'Pembayaran ' . $transactionStatus . ' - Order: ' . $orderId,
                ]);

                \Log::info('Payment ' . $transactionStatus . ':', [
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                ]);

            } else {
                \Log::warning('Unknown transaction status:', [
                    'order_id' => $orderId,
                    'transaction_status' => $transactionStatus,
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            \Log::error('Notification error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'payload' => $request->all(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * FINISH - Update dengan transaction_id
     */
    public function finish(Request $request)
    {
        try {
            \Log::info('Finish Callback:', $request->all());

            $orderId = $request->input('order_id');
            $transactionId = $request->input('transaction_id') ?? $orderId;
            $transactionStatus = $request->input('transaction_status') ?? 'pending';
            $paymentType = $request->input('payment_type');

            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if ($pembayaran) {
                $updateData = [
                    'transaction_id' => $transactionId,
                    'status' => $transactionStatus,
                ];

                if (in_array($transactionStatus, ['settlement', 'capture', 'success'])) {
                    $updateData['status'] = 'confirmed';
                    $updateData['tanggal_bayar'] = now();
                    $updateData['tanggal_konfirmasi'] = now();
                    $updateData['keterangan'] = 'Pembayaran berhasil via ' . strtoupper($paymentType ?? 'Midtrans') . ' | Order: ' . $orderId;
                } elseif ($transactionStatus === 'pending') {
                    $updateData['status'] = 'pending';
                    $updateData['keterangan'] = 'Menunggu pembayaran - ' . strtoupper($paymentType ?? 'Midtrans');
                } else {
                    $updateData['status'] = 'cancelled';
                    $updateData['keterangan'] = 'Pembayaran dibatalkan - ' . $transactionStatus;
                }

                if ($paymentType) {
                    $updateData['metode'] = $paymentType;
                }

                $pembayaran->update($updateData);

                // ✅ FIX 8: Update tenant di finish juga + cek sudah aktif
                if (in_array($transactionStatus, ['settlement', 'capture', 'success'])) {
                    $tenant = Tenant::find($pembayaran->tenant_id);

                    if ($tenant) {
                        $newPlanId = $pembayaran->plan_id;

                        $sudahAktif = $tenant->status_langganan === 'active'
                            && $tenant->plan_id == $newPlanId
                            && $tenant->tanggal_berakhir
                            && $tenant->tanggal_berakhir > now()->addDays(25);

                        if (!$sudahAktif) {
                            $tenant->update([
                                'status_langganan' => 'active',
                                'plan_id' => $newPlanId ?? $tenant->plan_id,
                                'tanggal_berakhir' => now()->addDays(30),
                                'max_user' => $newPlanId ? Plan::find($newPlanId)?->max_user ?? 10 : $tenant->max_user,
                            ]);

                            \Log::info('Tenant activated via finish:', [
                                'tenant_id' => $tenant->id_tenant,
                                'order_id' => $orderId,
                            ]);
                        }
                    }

                    session()->forget([
                        'pending_tenant_id',
                        'pending_order_id',
                        'upgrade_plan_id',
                        'pending_snap_token'
                    ]);
                }
            }

            return redirect()->route('status.langganan')
                ->with('success', 'Status pembayaran berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('Finish error: ' . $e->getMessage());
            return redirect()->route('status.langganan')
                ->with('error', 'Terjadi kesalahan.');
        }
    }

    private function getPlanIdFromAmount($amount)
    {
        if (!$amount)
            return null;
        return Plan::where('harga', $amount)->where('is_active', true)->first()?->id;
    }
}