<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\ProdukKonter;
use App\Models\StokHistory;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = BarangMasuk::with('produk_konter.voucher', 'produk_konter.cabang')
            ->where('tenant_id', $user->tenant_id);

        if ($request->filled('cabang_id')) {
            $query->whereHas('produk_konter', function ($q) use ($request) {
                $q->where('cabang_id', $request->cabang_id);
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            $query->whereDate('tanggal', Carbon::today());
            $request->merge(['tanggal' => Carbon::today()->toDateString()]);
        }

        $barangMasuks = $query->latest()->get();

        $produkKonters = ProdukKonter::with('voucher', 'cabang')
            ->where('tenant_id', $user->tenant_id)
            ->get();

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        $vouchers = Voucher::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('barang_masuk.index', compact('barangMasuks', 'produkKonters', 'cabangs', 'vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'qty' => 'required|integer|min:1',
            'cabang_id' => 'required|exists:cabangs,id',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try {
            // Cari atau buat produk_konter
            $produk = ProdukKonter::firstOrCreate(
                [
                    'voucher_id' => $request->voucher_id,
                    'cabang_id' => $request->cabang_id,
                    'tenant_id' => $user->tenant_id,
                ],
                [
                    'stok' => 0,
                    'keterangan' => null,
                ]
            );

            // Tambah stok
            $produk->increment('stok', $request->qty);

            // Catat barang masuk
            BarangMasuk::create([
                'produk_konter_id' => $produk->id,
                'qty' => $request->qty,
                'tanggal' => now()->toDateString(),
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
            ]);

            // Catat history
            StokHistory::create([
                'tenant_id' => $user->tenant_id,
                'cabang_id' => $request->cabang_id,
                'voucher_id' => $request->voucher_id,
                'jenis' => 'masuk',
                'qty' => $request->qty,
                'keterangan' => 'Barang masuk',
                'user_id' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $request->validate([
            'produk_konter_id' => 'required|exists:produk_konter,id',
            'qty' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            // Kembalikan stok lama
            $produkLama = ProdukKonter::find($barangMasuk->produk_konter_id);
            if ($produkLama) {
                $produkLama->decrement('stok', $barangMasuk->qty);
            }

            // Update data
            $barangMasuk->update([
                'produk_konter_id' => $request->produk_konter_id,
                'qty' => $request->qty,
                'tanggal' => $request->tanggal,
            ]);

            // Tambah stok baru
            $produkBaru = ProdukKonter::find($request->produk_konter_id);
            $produkBaru->increment('stok', $request->qty);

            DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Data diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        DB::beginTransaction();

        try {
            $produk = ProdukKonter::find($barangMasuk->produk_konter_id);
            if ($produk) {
                $produk->decrement('stok', $barangMasuk->qty);
            }

            $barangMasuk->delete();

            DB::commit();

            return redirect()->route('barang_masuk.index')->with('success', 'Data dihapus');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
}