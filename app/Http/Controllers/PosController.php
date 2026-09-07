<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Voucher;
use App\Models\ProdukKonter;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\StokHistory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    /**
     * Halaman POS/Kasir
     */
    public function index()
    {
        $user = Auth::user();

        // Produk tersedia di cabang user
        $produks = ProdukKonter::with(['voucher.kategori'])
            ->where('cabang_id', $user->cabang_id)
            ->where('stok', '>', 0)
            ->get();

        return view('frontend.pos.index', compact('produks'));
    }

    /**
     * Simpan Transaksi POS
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.voucher_id' => 'required|exists:vouchers,id',
            'items.*.qty' => 'required|integer|min:1',
            'bayar' => 'required|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $items = [];

            // Cek stok & hitung total
            foreach ($request->items as $item) {
                $produk = ProdukKonter::where('voucher_id', $item['voucher_id'])
                    ->where('cabang_id', $user->cabang_id)
                    ->first();

                if (!$produk || $produk->stok < $item['qty']) {
                    throw new \Exception('Stok tidak cukup untuk salah satu produk');
                }

                $voucher = Voucher::find($item['voucher_id']);
                $subtotal = $voucher->harga_jual * $item['qty'];
                $totalHarga += $subtotal;

                $items[] = [
                    'voucher_id' => $voucher->id,
                    'qty' => $item['qty'],
                    'harga_satuan' => $voucher->harga_jual,
                    'subtotal' => $subtotal,
                    'produk_konter' => $produk,
                ];
            }

            // ✅ Hitung diskon
            $diskon = $request->diskon ?? 0;
            $totalSetelahDiskon = $totalHarga - $diskon;

            // Validasi diskon tidak melebihi total
            if ($diskon > $totalHarga) {
                throw new \Exception('Diskon tidak boleh melebihi total');
            }

            // Validasi bayar pakai total setelah diskon
            if ($request->bayar < $totalSetelahDiskon) {
                throw new \Exception('Nominal bayar kurang');
            }

            // Simpan penjualan
            $penjualan = Penjualan::create([
                'kode_transaksi' => 'TRX-' . date('Ymd') . '-' . strtoupper(Str::random(6)), // ✅ Acak
                'user_id' => $user->id,
                'tenant_id' => $user->tenant_id,
                'cabang_id' => $user->cabang_id,
                'total_harga' => $totalHarga,
                'diskon' => $diskon, // ✅
                'total_setelah_diskon' => $totalSetelahDiskon, // ✅
                'bayar' => $request->bayar,
                'kembalian' => $request->bayar - $totalSetelahDiskon, // ✅
                'status' => 'lunas',
            ]);

            // Simpan detail & kurangi stok
            foreach ($items as $item) {
                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'tenant_id' => $user->tenant_id,
                    'cabang_id' => $user->cabang_id,
                    'voucher_id' => $item['voucher_id'],
                    'qty' => $item['qty'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Kurangi stok
                $item['produk_konter']->update([
                    'stok' => $item['produk_konter']->stok - $item['qty'],
                ]);

                // Catat history stok
                StokHistory::create([
                    'tenant_id' => $penjualan->tenant_id,
                    'cabang_id' => $penjualan->cabang_id,
                    'voucher_id' => $detail->voucher_id,
                    'jenis' => 'masuk', // stok kembali
                    'qty' => $detail->qty,
                    'keterangan' => 'Hapus transaksi #' . ($penjualan->kode_transaksi ?? 'TRX-' . $penjualan->id),
                    'user_id' => $user->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'penjualan_id' => $penjualan->id,
                'kode_transaksi' => $penjualan->kode_transaksi, // ✅
                'kembalian' => $penjualan->kembalian,
                'message' => 'Transaksi berhasil',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Laporan penjualan
     */
    public function laporan(Request $request)
    {
        $user = Auth::user();

        // ✅ Default: hari ini
        $startDate = $request->start_date ?? now()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $query = Penjualan::with(['details.voucher.kategori', 'user'])
            ->where('tenant_id', $user->tenant_id)
            ->where('cabang_id', $user->cabang_id)
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);

        $penjualans = $query->orderBy('created_at', 'asc')->get();

        $totalPenjualan = $penjualans->sum('total_setelah_diskon');
        $totalTransaksi = $penjualans->count();
        $totalProdukTerjual = PenjualanDetail::whereIn('penjualan_id', $penjualans->pluck('id'))
            ->sum('qty');

        return view('frontend.pos.laporan', compact(
            'penjualans',
            'totalPenjualan',
            'totalTransaksi',
            'totalProdukTerjual',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Laporan POS untuk Admin (semua cabang)
     */
    public function adminLaporan(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->start_date ?? now()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $query = Penjualan::with(['details.voucher.kategori', 'user', 'cabang'])
            ->where('tenant_id', $user->tenant_id)
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);

        // Filter cabang (admin bisa filter per cabang)
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $penjualans = $query->orderBy('created_at', 'asc')->get();

        $totalPenjualan = $penjualans->sum('total_setelah_diskon');
        $totalTransaksi = $penjualans->count();
        $totalProdukTerjual = PenjualanDetail::whereIn('penjualan_id', $penjualans->pluck('id'))->sum('qty');

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        $allVouchers = Voucher::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('admin_pos.laporan', compact(
            'penjualans',
            'totalPenjualan',
            'totalTransaksi',
            'totalProdukTerjual',
            'startDate',
            'endDate',
            'cabangs',
            'allVouchers'
        ));
    }

    /**
     * Hapus transaksi POS (Admin)
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $penjualan = Penjualan::with('details')
            ->where('tenant_id', $user->tenant_id)
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            foreach ($penjualan->details as $detail) {
                $produk = ProdukKonter::where('voucher_id', $detail->voucher_id)
                    ->where('cabang_id', $penjualan->cabang_id)
                    ->lockForUpdate()
                    ->first();

                if ($produk) {
                    $produk->increment('stok', $detail->qty);
                }

                StokHistory::create([
                    'tenant_id' => $penjualan->tenant_id,
                    'cabang_id' => $penjualan->cabang_id,
                    'voucher_id' => $detail->voucher_id,
                    'jenis' => 'hapus',
                    'qty' => $detail->qty,
                    'keterangan' => 'Hapus transaksi #' . ($penjualan->kode_transaksi ?? 'TRX-' . $penjualan->id),
                    'user_id' => $user->id,
                ]);
            }

            $penjualan->details()->delete();
            $penjualan->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Transaksi berhasil dihapus, stok dikembalikan');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Gagal hapus POS', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    /**
     * Edit transaksi POS (Admin)
     */
    public function edit($id)
    {
        $user = Auth::user();

        $penjualan = Penjualan::with(['details.voucher', 'cabang'])
            ->where('tenant_id', $user->tenant_id)
            ->findOrFail($id);

        return response()->json([
            'id' => $penjualan->id,
            'kode_transaksi' => $penjualan->kode_transaksi,
            'total_harga' => $penjualan->total_harga,
            'diskon' => $penjualan->diskon,
            'total_setelah_diskon' => $penjualan->total_setelah_diskon,
            'details' => $penjualan->details->map(function ($d) {
                return [
                    'id' => $d->id,
                    'voucher_id' => $d->voucher_id,
                    'nama_produk' => $d->voucher->nama_produk ?? '-',
                    'qty' => $d->qty,
                    'harga_satuan' => $d->harga_satuan,
                    'subtotal' => $d->subtotal,
                ];
            }),
        ]);
    }

    /**
     * Update transaksi POS (Admin)
     */
    public function update(Request $request, $id)
    {
        // ✅ Bersihkan diskon dari titik/koma
        $request->merge([
            'diskon' => (int) str_replace(['.', ','], '', $request->diskon),
        ]);

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.detail_id' => 'required|exists:penjualan_details,id',
            'items.*.voucher_id' => 'required|exists:vouchers,id', // ✅ Validasi voucher_id
            'items.*.qty' => 'required|integer|min:1',
            'diskon' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();

        $penjualan = Penjualan::where('tenant_id', $user->tenant_id)
            ->findOrFail($id);

        DB::beginTransaction();

        try {
            $totalHarga = 0;

            foreach ($request->items as $item) {
                $detail = PenjualanDetail::findOrFail($item['detail_id']);

                // ✅ Ambil harga dari voucher yang baru dipilih
                $voucher = Voucher::findOrFail($item['voucher_id']);
                $hargaSatuan = $voucher->harga_jual;

                $subtotal = $hargaSatuan * $item['qty'];
                $totalHarga += $subtotal;

                $detail->update([
                    'voucher_id' => $item['voucher_id'], // ✅ Update voucher_id
                    'qty' => $item['qty'],
                    'harga_satuan' => $hargaSatuan, // ✅ Update harga
                    'subtotal' => $subtotal,
                ]);
            }

            $diskon = $request->diskon ?? 0;
            $totalSetelahDiskon = $totalHarga - $diskon;

            $penjualan->update([
                'total_harga' => $totalHarga,
                'diskon' => $diskon,
                'total_setelah_diskon' => $totalSetelahDiskon,
            ]);

            DB::commit();

            return back()->with('success', 'Transaksi berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Export PDF laporan penjualan
     */
    public function laporanPdf(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->start_date ?? now()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $penjualans = Penjualan::with(['details.voucher.kategori', 'user'])
            ->where('tenant_id', $user->tenant_id)
            ->where('cabang_id', $user->cabang_id)
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        $totalPenjualan = $penjualans->sum('total_setelah_diskon');
        $totalTransaksi = $penjualans->count();
        $totalProdukTerjual = $penjualans->sum(function ($p) {
            return $p->details->sum('qty');
        });

        $pdf = \PDF::loadView('frontend.pos.pdf', compact(
            'penjualans',
            'totalPenjualan',
            'totalTransaksi',
            'totalProdukTerjual',
            'startDate',
            'endDate'
        ));

        return $pdf->download('laporan-pos-' . $startDate . '-sd-' . $endDate . '.pdf');
    }

    /**
     * Cetak struk
     */
    public function struk($kode)
    {
        $user = Auth::user();

        $penjualan = Penjualan::with(['details.voucher', 'user', 'cabang', 'tenant'])
            ->where('tenant_id', $user->tenant_id)
            ->where('cabang_id', $user->cabang_id)
            ->where('kode_transaksi', $kode) // ✅ Cari pakai kode
            ->firstOrFail();

        return view('frontend.pos.struk', compact('penjualan'));
    }

}