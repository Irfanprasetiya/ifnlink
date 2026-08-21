<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Cabang;
use App\Models\User;
use App\Models\Pengeluaran;
use App\Models\TransaksiBank;
use Illuminate\Http\Request;

class DevDashboardController extends Controller
{
    public function index()
    {
        $range = now()->startOfMonth();

        // Laba kotor global
        $semuaTrx = TransaksiBank::with('jenis_transaksi')
            ->where('waktu_transaksi', '>=', $range)
            ->whereHas('bank', fn($q) => $q->whereRaw('LOWER(nama_bank) != "kas"'))
            ->get();

        $totalLabaSistem = 0;
        foreach ($semuaTrx as $trx) {
            $jenis = strtolower($trx->jenis_transaksi->nama_transaksi ?? '');
            $nominal = $trx->nominal ?? 0;
            $bayar = $trx->bayar ?? 0;

            if ($jenis === 'tarik tunai') {
                $totalLabaSistem += ($nominal - $bayar);
            } elseif ($jenis === 'transfer') {
                $totalLabaSistem += ($bayar - $nominal);
            } elseif ($jenis === 'numpang transfer') {
                $totalLabaSistem += $bayar;
            }
        }

        // ✅ Volume transaksi dari database (bulan ini)
        $volumeTransaksi = TransaksiBank::where('waktu_transaksi', '>=', $range)
            ->whereHas('jenis_transaksi', fn($q) => $q->whereIn('nama_transaksi', ['Transfer', 'Tarik Tunai', 'Numpang Transfer']))
            ->count();

        // Stats
        $stats = [
            'omzet_global' => $totalLabaSistem,
            'volume_transaksi' => $volumeTransaksi,
            'total_agen' => Tenant::count(),
            'active_agen' => Tenant::where('status_langganan', 'active')->count(),
            'trial_agen' => Tenant::where('status_langganan', 'trial')->count(),
            'expired_agen' => Tenant::where('status_langganan', 'expired')->count(),
            'total_cabang' => Cabang::count(),
            'total_users' => User::count(),
            'total_transaksi' => TransaksiBank::count(),
            'total_pengeluaran' => Pengeluaran::sum('nominal'),
            'new_this_month' => Tenant::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Agen terbaru
        $agen_terbaru = Tenant::with('plan')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('developer.dashboard', compact('stats', 'agen_terbaru'));
    }
}