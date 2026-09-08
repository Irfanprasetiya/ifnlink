<?php

namespace App\Http\Controllers;

use App\Models\StokHistory;
use App\Models\Cabang;
use App\Models\ProdukKonter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokHistoryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->start_date ?? now()->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        $query = StokHistory::with(['voucher', 'cabang', 'user'])
            ->where('tenant_id', $user->tenant_id)
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);

        // Filter jenis
        if ($request->jenis) {
            $query->where('jenis', $request->jenis);
        }

        // Filter cabang
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(30);

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        $stokMenipis = ProdukKonter::with(['voucher', 'cabang'])
            ->where('tenant_id', $user->tenant_id)
            ->where('stok', '<=', 5)
            ->get();

        return view('stok_history.index', compact('histories', 'cabangs', 'startDate', 'endDate', 'stokMenipis'));
    }

    /**
     * Laporan stok tersisa
     */
    public function laporan()
    {
        $user = Auth::user();

        $stoks = ProdukKonter::with(['voucher.kategori', 'cabang'])
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('voucher_id')
            ->orderBy('cabang_id')
            ->get();

        $totalProduk = $stoks->count();
        $totalStok = $stoks->sum('stok');
        $stokMenipis = $stoks->where('stok', '<=', 5)->count();

        return view('stok_history.laporan', compact('stoks', 'totalProduk', 'totalStok', 'stokMenipis'));
    }

    /**
     * Cek stok menipis (untuk dashboard/badge)
     */
    public function stokMenipis()
    {
        $user = Auth::user();

        $stoks = ProdukKonter::with(['voucher', 'cabang'])
            ->where('tenant_id', $user->tenant_id)
            ->where('stok', '<=', 5)
            ->get();

        return response()->json([
            'count' => $stoks->count(),
            'items' => $stoks->map(function ($s) {
                return [
                    'nama_produk' => $s->voucher->nama_produk ?? '-',
                    'cabang' => $s->cabang->nama_cabang ?? '-',
                    'stok' => $s->stok,
                ];
            }),
        ]);
    }

    /**
     * Halaman stok menipis
     */
    public function halamanMenipis()
    {
        $user = Auth::user();

        $stokMenipis = ProdukKonter::with(['voucher', 'cabang'])
            ->where('tenant_id', $user->tenant_id)
            ->where('stok', '<=', 5)
            ->get();

        return view('stok_history.menipis', compact('stokMenipis'));
    }
}