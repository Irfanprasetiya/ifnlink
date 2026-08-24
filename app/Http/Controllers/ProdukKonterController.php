<?php

namespace App\Http\Controllers;

use App\Models\ProdukKonter;
use App\Models\Voucher;
use App\Models\Cabang;
use Illuminate\Http\Request;

class ProdukKonterController extends Controller
{
    public function index()
    {
        $produkKonters = ProdukKonter::with(['voucher', 'cabang'])->get();
        $vouchers = Voucher::all();
        $cabangs = Cabang::all();

        return view('data_master.produk_konter.index', compact('produkKonters', 'vouchers', 'cabangs'));
    }

    public function create()
    {
        $vouchers = Voucher::all();
        $cabangs = Cabang::all();
        return view('data_master.produk_konter.create', compact('vouchers', 'cabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'cabang_id' => 'required|exists:cabangs,id',
            'stok' => 'required|integer|min:0',
        ]);

        // Cek duplikat produk konter
        $exists = ProdukKonter::where('voucher_id', $request->voucher_id)
            ->where('cabang_id', $request->cabang_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Produk ini sudah ada. Silakan kunjungi halaman Barang Masuk untuk menambahkan stok.');
        }

        ProdukKonter::create([
            'voucher_id' => $request->voucher_id,
            'cabang_id' => $request->cabang_id,
            'stok' => $request->stok,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('data_master.produk_konter.index')->with('success', 'Produk konter berhasil ditambahkan.');
    }


    public function edit(ProdukKonter $produk_konter)
    {
        $vouchers = Voucher::all();
        $cabangs = Cabang::all();
        return view('data_master.produk_konter.edit', compact('produk_konter', 'vouchers', 'cabangs'));
    }

    public function update(Request $request, ProdukKonter $produk_konter)
    {
        $validated = $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'cabang_id' => 'required|exists:cabangs,id',
            'stok' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        $produk_konter->update($validated);

        return redirect()->route('data_master.produk_konter.index')
            ->with('success', 'Produk konter berhasil diperbarui');
    }

    public function destroy(ProdukKonter $produk_konter)
    {
        $produk_konter->delete();

        return redirect()->route('data_master.produk_konter.index')
            ->with('success', 'Produk konter berhasil dihapus');
    }
}
