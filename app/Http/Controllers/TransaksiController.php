<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use illuminate\Support\Facades\Auth;


class TransaksiController extends Controller
{
    public function index()
    {
        $transaksi = Transaksi::where('user_id', Auth::id())->get();
        return view('frontend.transaksi_konter.index', compact('transaksi'));
    }

    public function create()
    {
        return view('frontend.transaksi_konter.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required',
            'satuan_harga' => 'required|integer',
            'harga_grosir' => 'required|integer',
            'qty' => 'required|integer',
            'total_belanja' => 'required|integer',
            'keterangan' => 'nullable',
            'jumlah' => 'required|integer',
        ]);

        Transaksi::create([
            'user_id' => Auth::id(),
            'nama_transaksi' => $request->nama_transaksi,
            'jumlah' => $request->jumlah,
        ]);

        return redirect('/dashboard');
    }
}

