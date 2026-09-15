<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QrisManualController extends Controller
{
    /**
     * Tampilkan halaman QRIS
     * Route: GET /payment/qris
     */
    public function show(Request $request)
    {
        $planId = $request->plan_id ?? session('pending_plan_id');
        $plan = Plan::findOrFail($planId);

        $tenantId = session('pending_tenant_id');
        if (!$tenantId && Auth::check()) {
            $tenantId = Auth::user()->tenant_id;
        }

        if (!$tenantId) {
            return redirect()->route('register')
                ->with('error', 'Sesi pendaftaran habis. Silakan daftar ulang.');
        }

        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            return redirect()->route('register')
                ->with('error', 'Tenant tidak ditemukan.');
        }

        // ✅ Batalkan Midtrans pending saat user pilih QRIS
        $cancelledMidtrans = Pembayaran::where('tenant_id', $tenantId)
            ->where('metode', 'midtrans')
            ->where('status', 'pending')
            ->get();

        foreach ($cancelledMidtrans as $midtrans) {
            $midtrans->update([
                'status' => 'cancelled',
                'keterangan' => 'Dibatalkan - User memilih metode QRIS. Order lama: ' . $midtrans->order_id,
            ]);

            Log::info('Midtrans pending cancelled - User pilih QRIS:', [
                'order_id' => $midtrans->order_id,
                'tenant_id' => $tenantId,
            ]);
        }

        // Cek sudah bayar 30 hari terakhir
        $alreadyPaid = Pembayaran::where('tenant_id', $tenantId)
            ->whereIn('status', ['confirmed', 'settlement', 'capture'])
            ->where('tanggal_konfirmasi', '>=', now()->subDays(30))
            ->latest('tanggal_konfirmasi')
            ->first();

        if ($alreadyPaid) {
            return redirect()->route('status.langganan')
                ->with('success', 'Pembayaran Anda sudah selesai.');
        }

        // Cek pembayaran QRIS yang masih pending
        $pembayaran = Pembayaran::where('tenant_id', $tenantId)
            ->where('plan_id', $planId)
            ->where('metode', 'qris_manual')
            ->whereIn('status', ['pending', 'menunggu_verifikasi', 'rejected'])
            ->latest()
            ->first();

        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'tenant_id' => $tenantId,
                'plan_id' => $planId,
                'order_id' => Pembayaran::generateOrderIdQrisManual($tenantId),
                'jumlah' => $plan->harga,
                'status' => 'pending',
                'metode' => 'qris_manual',
                'keterangan' => 'Menunggu pembayaran via QRIS - ' . $plan->nama_paket,
            ]);
        }

        session([
            'pending_plan_id' => $planId,
        ]);

        return view('payment.qris', compact('plan', 'tenant', 'pembayaran'));
    }

    /**
     * Upload bukti transfer
     * Route: POST /payment/qris/upload
     */
    public function uploadBukti(Request $request)
    {
        $request->validate([
            'pembayaran_id' => 'required|exists:pembayarans,id',
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ], [
            'bukti.required' => 'Bukti transfer wajib diupload.',
            'bukti.image' => 'File harus berupa gambar.',
            'bukti.mimes' => 'Format harus JPG, JPEG, atau PNG.',
            'bukti.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $pembayaran = Pembayaran::findOrFail($request->pembayaran_id);

        // Validasi: harus QRIS manual & status pending/rejected
        if (!$pembayaran->isQrisManual()) {
            return back()->with('error', 'Pembayaran ini bukan QRIS Manual.');
        }

        if (!$pembayaran->canUploadBukti()) {
            return back()->with('error', 'Pembayaran ini tidak bisa upload bukti lagi.');
        }

        try {
            // ============================================
            // ✅ 1. AUTO-CREATE FOLDER
            // ============================================
            $folderName = 'bukti-qris';
            $storageFolder = storage_path('app/public/' . $folderName);

            if (!file_exists($storageFolder)) {
                if (!mkdir($storageFolder, 0755, true)) {
                    throw new \Exception('Gagal membuat folder: ' . $storageFolder);
                }
                Log::info('Folder created:', ['path' => $storageFolder]);
            }

            if (!is_writable($storageFolder)) {
                throw new \Exception('Folder tidak writable: ' . $storageFolder);
            }

            // ============================================
            // ✅ 2. HAPUS FILE LAMA (kalau ada)
            // ============================================
            if ($pembayaran->bukti_pembayaran) {
                $oldFile = storage_path('app/public/' . $pembayaran->bukti_pembayaran);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            // ============================================
            // ✅ 3. SIMPAN FILE BARU
            // ============================================
            $file = $request->file('bukti');
            $filename = 'bukti_' . time() . '_' . $pembayaran->id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folderName, $filename, 'public');

            // ============================================
            // ✅ 4. VERIFIKASI FILE TERSIMPAN
            // ============================================
            $savedPath = storage_path('app/public/' . $path);

            if (!file_exists($savedPath)) {
                throw new \Exception('File gagal tersimpan: ' . $savedPath);
            }

            if (filesize($savedPath) < 100) {
                throw new \Exception('File terlalu kecil, mungkin corrupt.');
            }

            Log::info('QRIS Bukti Uploaded:', [
                'pembayaran_id' => $pembayaran->id,
                'order_id' => $pembayaran->order_id,
                'tenant_id' => $pembayaran->tenant_id,
                'path' => $path,
                'full_path' => $savedPath,
                'size' => filesize($savedPath),
                'url' => Storage::url($path),
            ]);

            // ============================================
            // ✅ 5. UPDATE DB
            // ============================================
            $pembayaran->update([
                'bukti_pembayaran' => $path,
                'status' => 'menunggu_verifikasi',
                'keterangan' => 'Bukti transfer diupload. Menunggu verifikasi admin.',
            ]);

            return redirect()->route('qris.status', $pembayaran->order_id)
                ->with('success', '✅ Bukti transfer berhasil diupload. Mohon tunggu verifikasi admin (maks 1x24 jam).');

        } catch (\Exception $e) {
            Log::error('QRIS Upload Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'pembayaran_id' => $request->pembayaran_id,
                'file_info' => $request->hasFile('bukti') ? [
                    'name' => $request->file('bukti')->getClientOriginalName(),
                    'size' => $request->file('bukti')->getSize(),
                    'mime' => $request->file('bukti')->getMimeType(),
                ] : 'no file',
            ]);

            return back()->with('error', 'Gagal upload bukti: ' . $e->getMessage());
        }
    }

    /**
     * Halaman status pembayaran
     * Route: GET /payment/qris/status/{orderId}
     */
    public function status($orderId)
    {
        $pembayaran = Pembayaran::with(['tenant', 'plan', 'verifier'])
            ->where('order_id', $orderId)
            ->firstOrFail();

        $tenantId = session('pending_tenant_id') ?? Auth::user()?->tenant_id;
        if ($tenantId && $pembayaran->tenant_id != $tenantId) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('payment.qris-status', compact('pembayaran'));
    }

    /**
     * Cek status via AJAX (untuk polling)
     * Route: GET /payment/qris/check/{orderId}
     */
    public function checkStatus($orderId)
    {
        $pembayaran = Pembayaran::where('order_id', $orderId)->firstOrFail();

        return response()->json([
            'status' => $pembayaran->status,
            'status_label' => $pembayaran->status_label,
            'message' => $this->getStatusMessage($pembayaran->status),
        ]);
    }

    /**
     * Helper: pesan status
     */
    private function getStatusMessage($status): string
    {
        return match ($status) {
            'pending' => 'Menunggu pembayaran Anda',
            'menunggu_verifikasi' => 'Bukti sedang diverifikasi admin',
            'confirmed' => 'Pembayaran disetujui, akun aktif',
            'rejected' => 'Pembayaran ditolak',
            'expired' => 'Pembayaran kadaluarsa',
            default => 'Status tidak diketahui',
        };
    }
}