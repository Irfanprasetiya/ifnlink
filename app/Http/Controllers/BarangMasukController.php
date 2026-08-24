<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\ProdukKonter;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with('produk_konter.voucher', 'produk_konter.cabang');

        // Filter berdasarkan cabang
        if ($request->filled('cabang_id')) {
            $query->whereHas('produk_konter', function ($q) use ($request): void {
                $q->where('cabang_id', $request->cabang_id);
            });
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            // default: tampilkan data hari ini
            $query->whereDate('tanggal', Carbon::today());
            $request->merge(['tanggal' => Carbon::today()->toDateString()]);
        }

        // Ambil data hasil filter
        $barangMasuks = $query->latest()->get();

        // Data untuk dropdown filter dan form input
        $produkKonters = ProdukKonter::with('voucher', 'cabang')->get();
        $cabangs = Cabang::all();

        return view('barang_masuk.index', compact('barangMasuks', 'produkKonters', 'cabangs'));
    }


    public function create()
    {
        $produkKonters = ProdukKonter::with('voucher')->get();
        return view('barang_masuk.create', compact('produkKonters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_konter_id' => 'required|exists:produk_konter,id',
            'qty' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        // Tambah data barang masuk
        BarangMasuk::create($request->all());

        // Update stok di produk_konter
        $produk = ProdukKonter::findOrFail($request->produk_konter_id);
        $produk->stok += $request->qty;
        $produk->save();

        return redirect()->route('barang_masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }
}

