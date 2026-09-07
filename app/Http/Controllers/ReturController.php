<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use App\Models\ReturDetail;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\ProdukKonter;
use App\Models\Voucher;
use App\Models\StokHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{

    /**
     * Form ajukan retur (User)
     */
    public function create()
    {
        $user = Auth::user();

        // Produk tersedia di cabang user
        $produks = ProdukKonter::with('voucher')
            ->where('cabang_id', $user->cabang_id)
            ->where('stok', '>', 0)
            ->get();

        return view('frontend.retur.create', compact('produks'));
    }

    /**
     * Simpan pengajuan retur (User)
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.voucher_id' => 'required|exists:vouchers,id',
            'items.*.qty' => 'sometimes|integer|min:0',
            'alasan' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Filter item dengan qty > 0
        $items = collect($request->items)->filter(function ($item) {
            return ($item['qty'] ?? 0) > 0;
        });

        if ($items->isEmpty()) {
            return back()->with('error', 'Pilih minimal satu produk dengan qty lebih dari 0');
        }

        DB::beginTransaction();

        try {
            $totalRefund = 0;
            $returItems = [];

            foreach ($items as $item) {
                $produk = ProdukKonter::where('voucher_id', $item['voucher_id'])
                    ->where('cabang_id', $user->cabang_id)
                    ->first();

                if (!$produk || $produk->stok < $item['qty']) {
                    throw new \Exception('Stok tidak cukup');
                }

                $voucher = Voucher::find($item['voucher_id']);
                $subtotal = $voucher->harga_jual * $item['qty'];
                $totalRefund += $subtotal;

                $returItems[] = [
                    'voucher_id' => $voucher->id,
                    'qty' => $item['qty'],
                    'harga_satuan' => $voucher->harga_jual,
                    'subtotal' => $subtotal,
                ];
            }

            $retur = Retur::create([
                'kode_retur' => 'RET-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'tenant_id' => $user->tenant_id,
                'cabang_id' => $user->cabang_id,
                'user_id' => $user->id,
                'total_refund' => $totalRefund,
                'alasan' => $request->alasan,
                'status' => 'pending',
            ]);

            foreach ($returItems as $item) {
                ReturDetail::create([
                    'retur_id' => $retur->id,
                    'voucher_id' => $item['voucher_id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();

            return redirect()->route('retur.riwayat')
                ->with('success', 'Pengajuan retur berhasil dikirim, menunggu approval admin');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Riwayat retur (User)
     */
    public function riwayat()
    {
        $user = Auth::user();

        $returs = Retur::with(['details.voucher', 'penjualan'])
            ->where('tenant_id', $user->tenant_id)
            ->where('cabang_id', $user->cabang_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('frontend.retur.riwayat', compact('returs'));
    }

    /**
     * Daftar retur (Admin)
     */
    public function index()
    {
        $user = Auth::user();

        $returs = Retur::with(['details.voucher', 'user', 'cabang', 'penjualan'])
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('retur.index', compact('returs'));
    }

    /**
     * Detail retur (Admin)
     */
    public function show($id)
    {
        $user = Auth::user();

        $retur = Retur::with(['details.voucher', 'user', 'cabang', 'penjualan.details.voucher'])
            ->where('tenant_id', $user->tenant_id)
            ->findOrFail($id);

        return view('retur.show', compact('retur'));
    }

    /**
     * Approve retur (Admin)
     */
    public function approve($id)
    {
        $user = Auth::user();

        $retur = Retur::where('tenant_id', $user->tenant_id)
            ->where('status', 'pending')
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            // ✅ Kurangi stok (barang rusak dikeluarkan)
            foreach ($retur->details as $detail) {
                $produk = ProdukKonter::where('voucher_id', $detail->voucher_id)
                    ->where('cabang_id', $retur->cabang_id)
                    ->first();

                if ($produk) {
                    $produk->update([
                        'stok' => $produk->stok - $detail->qty, // ✅ Kurangi
                    ]);
                }

                StokHistory::create([
                    'tenant_id' => $retur->tenant_id,
                    'cabang_id' => $retur->cabang_id,
                    'voucher_id' => $detail->voucher_id,
                    'jenis' => 'retur', // Jenis retur = barang keluar
                    'qty' => $detail->qty,
                    'keterangan' => 'Retur #' . $retur->kode_retur,
                    'user_id' => $user->id,
                ]);
            }

            $retur->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Retur disetujui, stok dikurangi');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reject retur (Admin)
     */
    public function reject($id)
    {
        $user = Auth::user();

        $retur = Retur::where('tenant_id', $user->tenant_id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $retur->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Retur ditolak');
    }
}