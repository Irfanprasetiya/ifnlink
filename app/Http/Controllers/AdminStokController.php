<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\ProdukKonter;
use App\Models\StokHistory;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStokController extends Controller
{
    /**
     * Daftar stok produk per cabang
     */
    public function index()
    {
        $user = Auth::user();

        $stoks = ProdukKonter::with(['voucher.kategori', 'cabang'])
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('cabang_id')
            ->orderBy('voucher_id')
            ->get();

        return view('admin.stok.index', compact('stoks'));
    }

    /**
     * Form input stok masuk
     */
    public function create()
    {
        $user = Auth::user();

        $vouchers = Voucher::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->orderBy('nama_produk')
            ->get();

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->orderBy('nama_cabang')
            ->get();

        return view('admin.stok.create', compact('vouchers', 'cabangs'));
    }

    /**
     * Simpan stok masuk
     */
    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'cabang_id' => 'required|exists:cabangs,id',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $user = Auth::user();

        DB::beginTransaction();

        try {
            // Cari atau buat stok produk di cabang
            $produk = ProdukKonter::where('voucher_id', $request->voucher_id)
                ->where('cabang_id', $request->cabang_id)
                ->first();

            if ($produk) {
                $produk->update([
                    'stok' => $produk->stok + $request->qty,
                    'keterangan' => $request->keterangan ?? $produk->keterangan,
                ]);
            } else {
                ProdukKonter::create([
                    'voucher_id' => $request->voucher_id,
                    'cabang_id' => $request->cabang_id,
                    'stok' => $request->qty,
                    'keterangan' => $request->keterangan,
                ]);
            }

            // Catat history
            StokHistory::create([
                'tenant_id' => $user->tenant_id,
                'cabang_id' => $request->cabang_id,
                'voucher_id' => $request->voucher_id,
                'jenis' => 'masuk',
                'qty' => $request->qty,
                'keterangan' => $request->keterangan ?? 'Input stok masuk',
                'user_id' => $user->id,
            ]);

            DB::commit();

            return redirect()->route('admin.stok.index')
                ->with('success', 'Stok berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal input stok: ' . $e->getMessage());
        }
    }

    /**
     * Riwayat stok
     */
    public function riwayat()
    {
        $user = Auth::user();

        $histories = StokHistory::with(['voucher', 'cabang', 'user'])
            ->where('tenant_id', $user->tenant_id)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.stok.riwayat', compact('histories'));
    }
}