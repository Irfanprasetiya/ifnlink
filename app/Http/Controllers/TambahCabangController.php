<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class TambahCabangController extends Controller
{
    public function index()
    {
        $cabang = Cabang::all();
        return view('data_master.cabang.index', compact('cabang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat_cabang' => 'required|string|max:255|unique:cabang,alamat_cabang',
            'keterangan' => 'required|string|max:255',
        ]);

        Cabang::create($request->only('nama_cabang', 'alamat_cabang', 'keterangan'));

        return redirect()->route('data_master.cabang.index')->with('success', 'Cabang berhasil ditambahkan.');
    }
}
