<?php

namespace App\Http\Controllers;

use App\Models\ProdukKonter;
use App\Models\Voucher;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ProdukKonterController
 * 
 * CATATAN: Ini adalah STOK PRODUK per cabang.
 * Bukan hanya voucher, tapi semua produk fisik.
 */
class ProdukKonterController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // ✅ Filter multi-tenant + filter cabang
        $query = ProdukKonter::with(['voucher', 'cabang'])
            ->where(function ($q) use ($user) {
                $q->where('tenant_id', $user->tenant_id)
                    ->orWhereNull('tenant_id');
            });

        // Filter cabang
        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $produkKonters = $query->get();

        $vouchers = Voucher::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('data_master.produk_konter.index', compact('produkKonters', 'vouchers', 'cabangs'));
    }

    public function create()
    {
        $user = Auth::user();

        $vouchers = Voucher::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('data_master.produk_konter.create', compact('vouchers', 'cabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'cabang_id' => 'required|exists:cabangs,id',
            'stok' => 'required|integer|min:0',
        ]);

        $user = Auth::user();

        // Cek duplikat stok produk per cabang
        $exists = ProdukKonter::where('voucher_id', $request->voucher_id)
            ->where('cabang_id', $request->cabang_id)
            ->where('tenant_id', $user->tenant_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Produk ini sudah ada di cabang tersebut. Gunakan menu Barang Masuk untuk menambah stok.');
        }

        ProdukKonter::create([
            'voucher_id' => $request->voucher_id,
            'cabang_id' => $request->cabang_id,
            'tenant_id' => $user->tenant_id, // ✅ Simpan tenant_id
            'stok' => $request->stok,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('data_master.produk_konter.index')->with('success', 'Stok produk berhasil ditambahkan.');
    }

    public function edit(ProdukKonter $produk_konter)
    {
        $user = Auth::user();

        $vouchers = Voucher::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('data_master.produk_konter.edit', compact('produk_konter', 'vouchers', 'cabangs'));
    }

    public function update(Request $request, ProdukKonter $produk_konter)
    {
        $validated = $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'cabang_id' => 'required|exists:cabangs,id',
            'stok' => 'required|integer',
            'keterangan' => 'nullable|string',
        ]);

        $produk_konter->update($validated);

        return redirect()->route('data_master.produk_konter.index')
            ->with('success', 'Stok produk berhasil diperbarui');
    }

    public function destroy(ProdukKonter $produk_konter)
    {
        $produk_konter->delete();

        return redirect()->route('data_master.produk_konter.index')
            ->with('success', 'Stok produk berhasil dihapus');
    }
}