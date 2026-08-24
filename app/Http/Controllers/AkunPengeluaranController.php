<?php

namespace App\Http\Controllers;

use App\Models\AkunPengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AkunPengeluaranController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $akunPengeluarans = AkunPengeluaran::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_akun', 'asc')
            ->get();

        return view('data_master.akun_pengeluaran.index', compact('akunPengeluarans'));
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $request->validate([
            'nama_akun' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cegah buat akun dengan nama master
        $masterNames = ['PDAM', 'Listrik', 'Internet'];
        if (in_array($request->nama_akun, $masterNames)) {
            return back()->with('error', 'Akun "' . $request->nama_akun . '" adalah data master dan tidak dapat dibuat ulang.')
                ->withInput();
        }

        AkunPengeluaran::create([
            'tenant_id' => $tenantId,
            'nama_akun' => $request->nama_akun,
            'keterangan' => $request->keterangan ?? '-',
            'datetime' => now(), // ✅ TAMBAHKAN INI
        ]);

        return redirect()->route('data_master.akun_pengeluaran.index')
            ->with('success', 'Akun pengeluaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id;

        $akun = AkunPengeluaran::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })->findOrFail($id);

        // Cegah edit data master
        if (is_null($akun->tenant_id)) {
            return back()->with('error', 'Akun "' . $akun->nama_akun . '" adalah data master dan tidak dapat diubah.');
        }

        $request->validate([
            'nama_akun' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $akun->update($request->only('nama_akun', 'keterangan'));

        return redirect()->route('data_master.akun_pengeluaran.index')
            ->with('success', 'Akun pengeluaran berhasil diupdate.');
    }

    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id;

        $akun = AkunPengeluaran::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })->findOrFail($id);

        // Cegah hapus data master
        if (is_null($akun->tenant_id)) {
            return back()->with('error', 'Akun "' . $akun->nama_akun . '" adalah data master dan tidak dapat dihapus.');
        }

        $akun->delete();

        return redirect()->route('data_master.akun_pengeluaran.index')
            ->with('success', 'Akun pengeluaran berhasil dihapus.');
    }
}