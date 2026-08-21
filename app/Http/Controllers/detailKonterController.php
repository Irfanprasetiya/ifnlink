<?php

namespace App\Http\Controllers;

use App\Models\ProdukKonter;
use Illuminate\Http\Request;

class detailKonterController extends Controller
{
    public function index()
    {
        return view('frontend.transaksi_konter.detail');
    }

    public function show($id)
    {
        $produk = ProdukKonter::with(['voucher', 'cabang'])->findOrFail($id);

        return view('frontend.transaksi_konter.detail', compact('produk'));
    }

}
