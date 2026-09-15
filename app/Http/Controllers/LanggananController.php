<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanggananController extends Controller
{
    public function index()
    {
        // ✅ Konsisten: ambil tenant langsung + eager load plan
        $user = Auth::user();
        $tenant = $user->tenant()->with('plan')->first();

        if (!$tenant) {
            return redirect()->route('login')
                ->with('error', 'Data tenant tidak ditemukan.');
        }

        $pembayarans = Pembayaran::where('tenant_id', $tenant->id_tenant)
            ->orderBy('created_at', 'desc')
            ->get();

        // ✅ Hanya dari database, bukan session
        $hasPendingPayment = $pembayarans->where('status', 'pending')->count() > 0;

        // ✅ Cek apakah tenant expired
        $isExpired = $tenant->status_langganan === 'expired'
            || ($tenant->status_langganan === 'active'
                && $tenant->tanggal_berakhir
                && now()->greaterThan($tenant->tanggal_berakhir));

        return view('status_langganan.index', compact(
            'tenant',
            'pembayarans',
            'hasPendingPayment',
            'isExpired'
        ));
    }

    /**
     * API untuk polling status pembayaran
     */
    public function cekStatus()
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant) {
            return response()->json(['has_pending' => false, 'data' => []]);
        }

        $pembayarans = Pembayaran::where('tenant_id', $tenant->id_tenant)
            ->orderBy('created_at', 'desc')
            ->get(['id', 'order_id', 'status', 'metode', 'jumlah', 'created_at']);

        $hasPending = $pembayarans->where('status', 'pending')->count() > 0;

        return response()->json([
            'has_pending' => $hasPending,
            'data' => $pembayarans->map(function ($p) {
                return [
                    'id' => $p->id,
                    'order_id' => $p->order_id,
                    'status' => $p->status,
                    'metode' => $p->metode,
                    'jumlah' => $p->jumlah,
                    'tanggal' => $p->created_at->format('d M Y H:i'),
                ];
            }),
        ]);
    }

    public function upgrade()
    {
        $tenant = Auth::user()->tenant;
        $plans = Plan::where('is_active', true)->where('harga', '>', 0)->get();
        return view('status_langganan.upgrade', compact('tenant', 'plans'));
    }

    public function prosesUpgrade(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $tenant = Auth::user()->tenant;

        session([
            'pending_tenant_id' => $tenant->id_tenant,
            'upgrade_plan_id' => $plan->id,
        ]);

        return redirect()->route('checkout', $plan->id);
    }

    /**
     * Perpanjang langganan — redirect ke checkout
     */
    public function perpanjang()
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant) {
            return redirect()->route('login')
                ->with('error', 'Data tenant tidak ditemukan.');
        }

        if (!$tenant->plan || $tenant->plan->harga == 0) {
            return back()->with('error', 'Paket gratis tidak perlu diperpanjang.');
        }

        if (!$tenant->plan_id) {
            return back()->with('error', 'Paket langganan tidak ditemukan. Silakan pilih paket.');
        }

        // ✅ Cek kalau sudah ada pending payment → arahkan ke checkout existing
        $existingPending = Pembayaran::where('tenant_id', $tenant->id_tenant)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingPending) {
            session([
                'pending_tenant_id' => $tenant->id_tenant,
                'upgrade_plan_id' => $existingPending->plan_id,
                'pending_order_id' => $existingPending->order_id,
            ]);

            return redirect()->route('checkout', $existingPending->plan_id)
                ->with('info', 'Anda sudah memiliki tagihan yang belum dibayar.');
        }

        // ✅ Tidak ada pending → buat session baru
        session([
            'pending_tenant_id' => $tenant->id_tenant,
            'upgrade_plan_id' => $tenant->plan_id,
        ]);

        return redirect()->route('checkout', $tenant->plan_id);
    }

    public function uploadBukti(Request $request)
    {
        $request->validate([
            'pembayaran_id' => 'required|exists:pembayarans,id',
            'bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pembayaran = Pembayaran::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($request->pembayaran_id);

        if ($request->hasFile('bukti')) {
            $file = $request->file('bukti');
            $filename = 'bukti_' . time() . '_' . $pembayaran->id . '.' . $file->extension();
            $file->storeAs('public/bukti_pembayaran', $filename);

            $pembayaran->update([
                'bukti_pembayaran' => $filename,
                'tanggal_bayar' => now(),
            ]);
        }

        return back()->with('success', 'Bukti pembayaran berhasil diupload!');
    }

    public function downloadInvoice($id)
    {
        $pembayaran = Pembayaran::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);
        $tenant = Auth::user()->tenant;

        $pdf = \PDF::loadView('status_langganan.invoice', compact('pembayaran', 'tenant'));
        return $pdf->download('invoice-' . $pembayaran->id . '.pdf');
    }

    /**
     * Batalkan tagihan pending
     */
    public function batalkan()
    {
        $tenant = Auth::user()->tenant;

        if (!$tenant) {
            return back()->with('error', 'Data tenant tidak ditemukan.');
        }

        // Hapus record pembayaran pending
        Pembayaran::where('tenant_id', $tenant->id_tenant)
            ->where('status', 'pending')
            ->delete();

        // Hapus session
        session()->forget([
            'pending_tenant_id',
            'pending_snap_token',
            'pending_order_id',
            'upgrade_plan_id'
        ]);

        // ✅ Jangan ubah status tenant — biarkan seperti sebelumnya
        // Status tenant tetap: active, expired, trial, dll
        // Hanya pembayaran pending yang dihapus

        return redirect()->route('status.langganan')
            ->with('success', 'Tagihan berhasil dibatalkan.');
    }
}