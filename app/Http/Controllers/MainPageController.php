<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainPageController extends Controller
{
    public function index()
    {
        $cabangId = Auth::user()->cabang_id;

        // Ambil semua voucher dan relasi produk_konter, meskipun tidak ada produk_konter di cabang tsb
        $vouchers = Voucher::with('produk_konter')->get();

        return view('frontend.transaksi_konter.index', compact('vouchers', 'cabangId'));
    }

}
