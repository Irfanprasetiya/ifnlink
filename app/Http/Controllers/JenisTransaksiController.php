<?php

namespace App\Http\Controllers;

use App\Models\JenisTransaksi;
use Illuminate\Http\Request;

class JenisTransaksiController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;

        // Ambil data milik tenant ATAU data master (tenant_id IS NULL)
        // Diurutkan: Data Master dulu di paling atas, sisanya abjad
        $jenisTransaksi = JenisTransaksi::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_transaksi', 'asc')
            ->get();

        return view('data_master.jenis_transaksi.index', compact('jenisTransaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_transaksi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        // Selalu kunci ke tenant_id yang sedang login
        JenisTransaksi::create([
            'nama_transaksi' => $request->nama_transaksi,
            'keterangan' => $request->keterangan ?? '-',
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        return back()->with('success', 'Jenis transaksi berhasil ditambahkan.');
    }

    public function update(Request $request, JenisTransaksi $jenisTransaksi)
    {
        // PROTEKSI: Jangan izinkan edit data master sistem (tenant_id NULL)
        if (is_null($jenisTransaksi->tenant_id)) {
            return back()->with('error', 'Data standar sistem bersifat Read-Only.');
        }

        // PROTEKSI: Pastikan hanya bisa edit milik sendiri
        if ($jenisTransaksi->tenant_id != auth()->user()->tenant_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'nama_transaksi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $jenisTransaksi->update($request->only('nama_transaksi', 'keterangan'));

        return back()->with('success', 'Jenis transaksi berhasil diperbarui.');
    }

    public function destroy(JenisTransaksi $jenisTransaksi)
    {
        // PROTEKSI: Jangan izinkan hapus data master sistem
        if (is_null($jenisTransaksi->tenant_id)) {
            return back()->with('error', 'Data standar sistem bersifat Read-Only dan tidak bisa dihapus.');
        }

        // PROTEKSI: Pastikan hanya bisa hapus milik sendiri
        if ($jenisTransaksi->tenant_id != auth()->user()->tenant_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $jenisTransaksi->delete();
        return back()->with('success', 'Jenis transaksi berhasil dihapus.');
    }
}