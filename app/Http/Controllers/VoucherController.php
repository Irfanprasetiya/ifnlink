<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * VoucherController
 * 
 * CATATAN: "Voucher" di sini adalah PRODUK UMUM.
 * Bisa digunakan untuk:
 * - Voucher pulsa
 * - Alat listrik
 * - Barang kelontong
 * - dll.
 */
class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // ✅ Query dasar dengan filter tenant + data master
        $query = Voucher::with('kategori')
            ->where(function ($q) use ($user) {
                $q->where('tenant_id', $user->tenant_id)
                    ->orWhereNull('tenant_id');
            });

        // ✅ Filter berdasarkan search (nama produk)
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // ✅ Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $vouchers = $query->orderBy('id', 'desc')->get();

        // ✅ Kategori untuk dropdown filter
        $kategoris = Kategori::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('data_master.vouchers.index', compact('vouchers', 'kategoris'));
    }

    public function create()
    {
        $user = Auth::user();

        $kategoris = Kategori::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('data_master.vouchers.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'keterangan' => 'nullable|string',
        ]);

        Voucher::create([
            'nama_produk' => $request->nama_produk,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id,
            'keterangan' => $request->keterangan ?? '-',
            'tenant_id' => Auth::user()->tenant_id, // ✅ Simpan tenant_id
        ]);

        return redirect()->route('data_master.vouchers.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Voucher $voucher)
    {
        $user = Auth::user();

        $kategoris = Kategori::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->get();

        return view('data_master.vouchers.edit', compact('voucher', 'kategoris'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'kategori_id' => 'required|exists:kategoris,id',
            'keterangan' => 'nullable|string',
        ]);

        $voucher->update([
            'nama_produk' => $request->nama_produk,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id,
            'keterangan' => $request->keterangan ?? '-',
        ]);

        return redirect()->route('data_master.vouchers.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('data_master.vouchers.index')->with('success', 'Produk berhasil dihapus.');
    }
}