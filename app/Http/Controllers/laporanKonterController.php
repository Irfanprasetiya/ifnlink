<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class laporanKonterController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal dari request atau default ke hari ini
        $tanggal = $request->tanggal ?? Carbon::now()->format('Y-m-d');

        $penjualans = Penjualan::with(['produkKonter.voucher'])
            ->where('user_id', auth()->id())
            ->whereDate('created_at', $tanggal)
            ->get();

        // Kirim tanggal juga agar ditampilkan di form input
        return view('frontend.transaksi_konter.laporan', compact('penjualans', 'tanggal'));
    }

    // public function show(Request $request)
    // {
    //     $query = Penjualan::with(['produkKonter.voucher'])
    //         ->where('user_id', auth()->id());

    //     // Filter berdasarkan tanggal jika tersedia
    //     if ($request->has('tanggal') && $request->tanggal != '') {
    //         $query->whereDate('created_at', $request->tanggal);
    //     }

    //     $penjualans = $query->get();

    //     return view('frontend.transaksi_konter.laporan', compact('penjualans'));
    // }

}
