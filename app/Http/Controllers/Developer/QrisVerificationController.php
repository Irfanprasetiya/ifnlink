<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QrisVerificationController extends Controller
{
    /**
     * List pembayaran QRIS yang perlu diverifikasi
     * Route: GET /developer/qris-verification
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with(['tenant', 'plan', 'verifier'])
            ->where('metode', 'qris_manual')
            ->orderByRaw("FIELD(status, 'menunggu_verifikasi', 'pending', 'confirmed', 'rejected', 'cancelled', 'expired')")
            ->orderBy('created_at', 'desc');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pembayarans = $query->paginate(20);

        // Summary
        $summary = [
            'menunggu' => Pembayaran::qrisManual()->where('status', 'menunggu_verifikasi')->count(),
            'pending' => Pembayaran::qrisManual()->where('status', 'pending')->count(),
            'approved_hari_ini' => Pembayaran::qrisManual()
                ->where('status', 'confirmed')
                ->whereDate('verified_at', today())
                ->count(),
            'total_hari_ini' => Pembayaran::qrisManual()
                ->where('status', 'confirmed')
                ->whereDate('verified_at', today())
                ->sum('jumlah'),
        ];

        return view('developer.qris.index', compact('pembayarans', 'summary'));
    }

    /**
     * Approve pembayaran QRIS
     * Route: POST /developer/qris-verification/{id}/approve
     */
    public function approve($id)
    {
        $pembayaran = Pembayaran::with(['tenant', 'plan'])->findOrFail($id);

        // Validasi
        if ($pembayaran->metode !== 'qris_manual') {
            return back()->with('error', 'Pembayaran ini bukan QRIS Manual.');
        }

        if ($pembayaran->status !== 'menunggu_verifikasi') {
            return back()->with('error', 'Pembayaran ini tidak bisa di-approve.');
        }

        DB::beginTransaction();
        try {
            $tenant = $pembayaran->tenant;
            $plan = $pembayaran->plan;

            if (!$tenant || !$plan) {
                throw new \Exception('Tenant atau Plan tidak ditemukan.');
            }

            // Update tenant
            $tenant->update([
                'plan_id' => $plan->id,
                'status_langganan' => 'active',
                'tanggal_berakhir' => now()->addDays(30),
                'max_user' => $plan->max_user ?? 1,
            ]);

            // Update pembayaran
            $pembayaran->update([
                'status' => 'confirmed',
                'tanggal_bayar' => now(),
                'tanggal_konfirmasi' => now(),
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'catatan_admin' => 'Disetujui oleh ' . Auth::user()->name,
                'keterangan' => 'Pembayaran QRIS disetujui | Order: ' . $pembayaran->order_id,
            ]);

            DB::commit();

            Log::info('QRIS Manual Approved:', [
                'pembayaran_id' => $pembayaran->id,
                'order_id' => $pembayaran->order_id,
                'tenant_id' => $tenant->id_tenant,
                'plan_id' => $plan->id,
                'approved_by' => Auth::id(),
            ]);

            return back()->with(
                'success',
                '✅ Pembayaran disetujui. Akun ' . $tenant->nama_toko . ' sudah aktif.'
            );

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('QRIS Approve Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal approve: ' . $e->getMessage());
        }
    }

    /**
     * Reject pembayaran QRIS
     * Route: POST /developer/qris-verification/{id}/reject
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
            'catatan.max' => 'Alasan maksimal 500 karakter.',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        // Validasi
        if ($pembayaran->metode !== 'qris_manual') {
            return back()->with('error', 'Pembayaran ini bukan QRIS Manual.');
        }

        if (!in_array($pembayaran->status, ['menunggu_verifikasi', 'pending'])) {
            return back()->with('error', 'Pembayaran ini tidak bisa di-reject.');
        }

        try {
            $pembayaran->update([
                'status' => 'rejected',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'catatan_admin' => $request->catatan,
                'keterangan' => 'Pembayaran ditolak: ' . $request->catatan,
            ]);

            Log::info('QRIS Manual Rejected:', [
                'pembayaran_id' => $pembayaran->id,
                'order_id' => $pembayaran->order_id,
                'rejected_by' => Auth::id(),
                'reason' => $request->catatan,
            ]);

            return back()->with(
                'warning',
                '⚠️ Pembayaran ditolak. User akan mendapat notifikasi.'
            );

        } catch (\Exception $e) {
            Log::error('QRIS Reject Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal reject: ' . $e->getMessage());
        }
    }

    /**
     * Detail pembayaran
     * Route: GET /developer/qris-verification/{id}
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['tenant', 'plan', 'verifier'])->findOrFail($id);

        return view('developer.qris.show', compact('pembayaran'));
    }
}