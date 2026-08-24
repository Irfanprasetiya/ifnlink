<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::with('kategori')->get();
        $kategoris = Kategori::all();

        return view('data_master.vouchers.index', compact('vouchers', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('data_master.vouchers.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'keterangan' => 'nullable|string',
        ]);

        // Alternatif sementara: beri nilai default jika keterangan null
        $data = $request->all();
        $data['keterangan'] = $data['keterangan'] ?? '-';

        Voucher::create($data);

        // Voucher::create($request->all());

        return redirect()->route('data_master.vouchers.index')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function edit(Voucher $voucher)
    {
        $kategoris = Kategori::all();
        return view('data_master.vouchers.edit', compact('voucher', 'kategoris'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'keterangan' => 'nullable|string',
        ]);

        $voucher->update($request->all());

        return redirect()->route('data_master.vouchers.index')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('data_master.vouchers.index')->with('success', 'Voucher berhasil dihapus.');
    }
}
