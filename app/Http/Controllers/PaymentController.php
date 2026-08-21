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
     * Format: OMZ-{tenant_id}-{timestamp}-{random6}
     * Contoh: OMZ-123-1692145800-AB12CD
     */
    private function generateOrderId($tenantId)
    {
        return 'OMZ-' . $tenantId . '-' . time() . '-' . strtoupper(Str::random(6));
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

        $isUpgrade = $tenant->plan_id != $planId;
        $isPerpanjang = $tenant->plan_id == $planId && $tenant->status_langganan === 'active';

        if ($tenant->status_langganan !== 'pending' && !$isUpgrade && !$isPerpanjang) {
            return redirect()->route('status.langganan')
                ->with('info', 'Pembayaran Anda sudah selesai. Tidak perlu checkout lagi.');
        }

        // Cek apakah sudah ada pembayaran pending untuk tenant ini
        $existingPayment = Pembayaran::where('tenant_id', $tenantId)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingPayment && $existingPayment->order_id) {
            // Pakai order_id yang sudah ada
            $orderId = $existingPayment->order_id;
        } else {
            // Generate order_id baru dengan format OMZ
            $orderId = $this->generateOrderId($tenantId);

            // Simpan pembayaran baru
            Pembayaran::create([
                'tenant_id' => $tenantId,
                'plan_id' => $planId,
                'order_id' => $orderId,
                'jumlah' => $plan->harga,
                'status' => 'pending',
                'metode' => 'midtrans',
                'keterangan' => 'Menunggu pembayaran - ' . $plan->nama_paket,
            ]);
        }

        // Simpan di session
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

        // ✅ Cek apakah ini permintaan "Ganti Metode" atau "Bayar Baru"
        $isNewPayment = $request->input('new_payment', false) || $request->input('ganti_metode', false);

        // ✅ Ambil order_id dari session atau request
        $orderId = session('pending_order_id')
            ?? $request->order_id
            ?? $request->input('order_id')
            ?? null;

        // ✅ Jika ini permintaan GANTI METODE, batalkan yang lama dan buat baru
        if ($isNewPayment) {
            // ✅ Batalkan semua pembayaran pending sebelumnya
            $cancelledPayments = Pembayaran::where('tenant_id', $tenantId)
                ->where('status', 'pending')
                ->get();

            foreach ($cancelledPayments as $cancelled) {
                $cancelled->update([
                    'status' => 'cancelled',
                    'keterangan' => 'Dibatalkan - Ganti metode pembayaran. Order lama: ' . $cancelled->order_id,
                ]);

                \Log::info('Pembayaran dibatalkan:', [
                    'order_id' => $cancelled->order_id,
                    'tenant_id' => $tenantId,
                    'reason' => 'Ganti metode pembayaran'
                ]);
            }

            // Hapus session token lama
            session()->forget('pending_snap_token');

            // Generate order_id baru
            $orderId = $this->generateOrderId($tenantId);

            // Update session dengan order_id baru
            session(['pending_order_id' => $orderId]);

            \Log::info('Ganti Metode - Membuat order_id baru:', [
                'new_order_id' => $orderId,
                'tenant_id' => $tenantId,
                'cancelled_count' => $cancelledPayments->count()
            ]);
        }

        // Jika tidak ada order_id, cari dari database atau buat baru
        if (!$orderId) {
            $existingPayment = Pembayaran::where('tenant_id', $tenantId)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($existingPayment && $existingPayment->order_id) {
                $orderId = $existingPayment->order_id;
            } else {
                // Buat baru dengan format OMZ
                $orderId = $this->generateOrderId($tenantId);
            }
        }

        // Log untuk debugging
        \Log::info('Pay Method - Order ID:', [
            'order_id' => $orderId,
            'is_new_payment' => $isNewPayment,
            'session_order_id' => session('pending_order_id'),
            'request_order_id' => $request->order_id,
            'tenant_id' => $tenantId
        ]);

        // Cari atau buat record pembayaran dengan order_id ini
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            // Buat baru jika tidak ada
            $pembayaran = Pembayaran::create([
                'tenant_id' => $tenantId,
                'plan_id' => $plan->id,
                'order_id' => $orderId,
                'jumlah' => $plan->harga,
                'status' => 'pending',
                'metode' => '-',
                'keterangan' => 'Menunggu pembayaran - ' . $plan->nama_paket,
            ]);
        } else {
            // Update jika perlu
            $pembayaran->update([
                'plan_id' => $plan->id,
                'jumlah' => $plan->harga,
                'status' => 'pending',
                'keterangan' => 'Menunggu pembayaran - ' . $plan->nama_paket,
            ]);
        }

        // Gunakan order_id yang SAMA untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId, // Format OMZ
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

            // Simpan order_id yang sama di session
            session([
                'pending_order_id' => $orderId,
                'pending_snap_token' => $snapToken,
                'upgrade_plan_id' => $plan->id,
            ]);

            return view('payment.pay', compact('snapToken', 'plan', 'tenant', 'orderId'));
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());

            // Jika error karena order_id sudah digunakan, coba buat order_id baru
            if (str_contains($e->getMessage(), 'order_id sudah digunakan')) {
                // Batalkan pembayaran pending yang ada
                Pembayaran::where('tenant_id', $tenantId)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'cancelled',
                        'keterangan' => 'Dibatalkan - Order ID sudah digunakan di Midtrans',
                    ]);

                // Generate order_id baru
                $newOrderId = $this->generateOrderId($tenantId);

                // Update session
                session(['pending_order_id' => $newOrderId]);

                // Buat record baru
                Pembayaran::create([
                    'tenant_id' => $tenantId,
                    'plan_id' => $plan->id,
                    'order_id' => $newOrderId,
                    'jumlah' => $plan->harga,
                    'status' => 'pending',
                    'metode' => '-',
                    'keterangan' => 'Menunggu pembayaran - ' . $plan->nama_paket,
                ]);

                // Update params dengan order_id baru
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

        // ✅ Batalkan semua pembayaran pending sebelumnya
        $cancelledPayments = Pembayaran::where('tenant_id', $tenant->id_tenant)
            ->where('status', 'pending')
            ->get();

        foreach ($cancelledPayments as $cancelled) {
            $cancelled->update([
                'status' => 'cancelled',
                'keterangan' => 'Dibatalkan - Pembayaran ulang. Order lama: ' . $cancelled->order_id,
            ]);
        }

        // ✅ Buat order_id BARU
        $orderId = $this->generateOrderId($tenant->id_tenant);

        // Buat record baru
        Pembayaran::create([
            'tenant_id' => $tenant->id_tenant,
            'plan_id' => $plan->id,
            'order_id' => $orderId,
            'jumlah' => $plan->harga,
            'status' => 'pending',
            'metode' => '-',
            'keterangan' => 'Pembayaran ulang - ' . $plan->nama_paket,
        ]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId, // Format OMZ baru
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

            $orderId = $payload['order_id'] ?? null;
            $transactionId = $payload['transaction_id'] ?? $orderId;
            $statusCode = $payload['status_code'] ?? null;
            $grossAmount = $payload['gross_amount'] ?? null;
            $signatureKey = $payload['signature_key'] ?? null;
            $transactionStatus = $payload['transaction_status'] ?? null;
            $paymentType = $payload['payment_type'] ?? null;
            $vaNumber = $payload['va_numbers'][0]['va_number'] ?? null;
            $bank = $payload['va_numbers'][0]['bank'] ?? $payload['bank'] ?? null;

            // Verifikasi signature jika tersedia
            if ($signatureKey && $statusCode && $grossAmount) {
                $serverKey = config('midtrans.server_key');
                $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

                if (!hash_equals($expectedSignature, $signatureKey)) {
                    \Log::warning('Invalid signature', ['order_id' => $orderId]);
                    return response()->json(['status' => 'invalid signature'], 403);
                }
            }

            // Cari pembayaran
            $pembayaran = Pembayaran::where('order_id', $orderId)->first();

            if (!$pembayaran) {
                \Log::warning('Payment not found', ['order_id' => $orderId]);
                return response()->json(['status' => 'payment not found'], 404);
            }

            $tenant = Tenant::find($pembayaran->tenant_id);

            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                // Update tenant
                if ($tenant) {
                    $newPlanId = $pembayaran->plan_id
                        ?? session('upgrade_plan_id')
                        ?? $this->getPlanIdFromAmount($grossAmount);

                    $tenant->update([
                        'status_langganan' => 'active',
                        'plan_id' => $newPlanId ?? $tenant->plan_id,
                        'tanggal_berakhir' => now()->addDays(30),
                        'max_user' => $newPlanId ? Plan::find($newPlanId)?->max_user ?? 10 : $tenant->max_user,
                    ]);
                }

                // Update pembayaran
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
                $pembayaran->update([
                    'transaction_id' => $transactionId,
                    'status' => 'pending',
                    'metode' => $paymentType,
                    'keterangan' => 'Menunggu pembayaran - ' . strtoupper($paymentType ?? 'Midtrans'),
                ]);

            } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny', 'failure'])) {
                $pembayaran->update([
                    'transaction_id' => $transactionId,
                    'status' => $transactionStatus === 'expire' ? 'expired' : 'cancelled',
                    'keterangan' => 'Pembayaran ' . $transactionStatus . ' - Order: ' . $orderId,
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            \Log::error('Notification error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
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

                if (in_array($transactionStatus, ['settlement', 'capture', 'success'])) {
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