<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabangController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        // Ambil data milik tenant ATAU data master (tenant_id IS NULL)
        // Diurutkan: Data Master (Gudang) dulu paling atas, sisanya sesuai abjad
        $cabangs = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_cabang', 'asc')
            ->get();

        return view('data_master.cabang.index', compact('cabangs'));
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'nama_cabang' => 'required|string|max:255|unique:cabangs,nama_cabang,NULL,id,tenant_id,' . $tenantId,
            'alamat_cabang' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cegah buat cabang dengan nama "Gudang"
        if (strtolower($request->nama_cabang) === 'gudang') {
            return back()->with('error', 'Nama cabang "Gudang" sudah digunakan untuk Saldo Pusat.')
                ->withInput();
        }

        $data = $request->only('nama_cabang', 'alamat_cabang', 'keterangan');
        $data['tenant_id'] = $tenantId;

        if (empty($data['keterangan'])) {
            $data['keterangan'] = '-';
        }

        Cabang::create($data);

        return redirect()->route('data_master.cabang.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id;

        // Cari cabang: bisa milik tenant atau data master
        $cabang = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })->findOrFail($id);

        // Cegah edit cabang data master (tenant_id = NULL)
        if (is_null($cabang->tenant_id)) {
            return back()->with('error', 'Cabang Gudang (Saldo Pusat) adalah data master dan tidak dapat diubah.');
        }

        $request->validate([
            'nama_cabang' => 'required|string|max:255',
            'alamat_cabang' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cegah ubah nama jadi "Gudang"
        if (strtolower($request->nama_cabang) === 'gudang') {
            return back()->with('error', 'Nama cabang "Gudang" hanya untuk Saldo Pusat.')
                ->withInput();
        }

        $cabang->update($request->only('nama_cabang', 'alamat_cabang', 'keterangan'));

        return redirect()->route('data_master.cabang.index')
            ->with('success', 'Cabang berhasil diupdate.');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;

        // Cari cabang: bisa milik tenant atau data master
        $cabang = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })->findOrFail($id);

        // Cegah hapus data master
        if (is_null($cabang->tenant_id)) {
            return back()->with('error', 'Cabang Gudang (Saldo Pusat) adalah data master dan tidak dapat dihapus.');
        }

        // Cek apakah masih ada user di cabang ini
        if ($cabang->users()->count() > 0) {
            return back()->with('error', 'Cabang masih memiliki user. Pindahkan user terlebih dahulu.');
        }

        $cabang->delete();

        return redirect()->route('data_master.cabang.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}