<?php

// app/Http/Controllers/PenjualanController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class PenjualanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_konter_id' => 'required|exists:produk_konter,id',
            'qty' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $produk = \App\Models\ProdukKonter::findOrFail($request->produk_konter_id);

        // Cek apakah stok mencukupi
        if ($produk->stok < $request->qty) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // Buat penjualan
        $penjualan = new \App\Models\Penjualan();
        $penjualan->user_id = auth()->id();
        $penjualan->produk_konter_id = $request->produk_konter_id;
        $penjualan->qty = $request->qty;
        $penjualan->harga = $request->harga;
        $penjualan->harga_grosir = $request->harga_grosir ?? 0;
        $penjualan->total_harga = $request->total_harga;
        $penjualan->keterangan = $request->keterangan;
        $penjualan->save();

        // Kurangi stok
        $produk->stok -= $request->qty;
        $produk->save();

        return redirect()->route('laporan_konter')->with('success', 'Penjualan berhasil disimpan dan stok diperbarui.');
    }

}

