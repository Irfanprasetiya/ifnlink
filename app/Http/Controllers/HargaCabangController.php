<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\HargaCabang;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HargaCabangController extends Controller
{
    /**
     * List semua harga custom per cabang
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $cabangs = Cabang::where('tenant_id', $user->tenant_id)->get();

        $query = HargaCabang::with(['voucher', 'cabang'])
            ->where('tenant_id', $user->tenant_id);

        // Filter by cabang
        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->aktif();
            } elseif ($request->status === 'nonaktif') {
                $query->where('is_active', false);
            }
        }

        $hargaCabangs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Untuk dropdown
        $vouchers = Voucher::where('tenant_id', $user->tenant_id)
            ->orWhereNull('tenant_id')
            ->orderBy('nama_produk')
            ->get();

        return view('harga-cabang.index', compact('hargaCabangs', 'cabangs', 'vouchers'));
    }

    /**
     * Simpan harga baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:cabangs,id',
            'voucher_id' => 'required|exists:vouchers,id',
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'catatan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Cek duplikat — harga aktif tanpa tanggal untuk cabang & voucher yang sama
            $existing = HargaCabang::where('tenant_id', $user->tenant_id)
                ->where('cabang_id', $request->cabang_id)
                ->where('voucher_id', $request->voucher_id)
                ->where('is_active', true)
                ->whereNull('tanggal_mulai')
                ->exists();

            if ($existing) {
                throw new \Exception('Harga untuk produk & cabang ini sudah ada.');
            }

            $harga = HargaCabang::create([
                'tenant_id' => $user->tenant_id,
                'cabang_id' => $request->cabang_id,
                'voucher_id' => $request->voucher_id,
                'harga_jual' => $request->harga_jual,
                'harga_beli' => $request->harga_beli,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_berakhir' => $request->tanggal_berakhir,
                'is_active' => true,
                'catatan' => $request->catatan,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Harga cabang berhasil disimpan.',
                'data' => $harga,
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update harga
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'harga_jual' => 'required|numeric|min:0',
            'harga_beli' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'is_active' => 'boolean',
            'catatan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        $harga = HargaCabang::where('tenant_id', $user->tenant_id)
            ->findOrFail($id);

        $harga->update([
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'is_active' => $request->has('is_active') ? $request->is_active : $harga->is_active,
            'catatan' => $request->catatan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Harga cabang berhasil diupdate.',
        ]);
    }

    /**
     * Hapus harga
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $harga = HargaCabang::where('tenant_id', $user->tenant_id)
            ->findOrFail($id);

        $harga->delete();

        return response()->json([
            'success' => true,
            'message' => 'Harga cabang berhasil dihapus.',
        ]);
    }
}