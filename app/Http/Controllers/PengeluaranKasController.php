<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use App\Models\AkunPengeluaran;
use App\Models\Cabang;

class PengeluaranKasController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        // Hanya ambil Akun & Cabang milik tenant yang login untuk dropdown di form
        $akun = AkunPengeluaran::where('tenant_id', $tenantId)->get();
        $cabangs = Cabang::where('tenant_id', $tenantId)->get();

        // Query Utama: Filter berdasarkan tenant_id
        $query = Pengeluaran::with(['akun', 'cabang'])
            ->where('tenant_id', $tenantId);

        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->cabang_id) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $data = $query->latest()->get();

        return view('pengeluaran.index', compact(
            'data',
            'cabangs',
            'akun'
        ));
    }

    public function store(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $request->validate([
            'tanggal' => 'required|date',
            'akun_pengeluaran_id' => 'required|exists:akun_pengeluarans,id,tenant_id,' . $tenantId,
            'cabang_id' => 'required|exists:cabangs,id,tenant_id,' . $tenantId,
            'nominal' => 'required|numeric|min:0'
        ]);

        // Injeksi tenant_id secara otomatis agar tidak bisa dimanipulasi
        $data = $request->all();
        $data['tenant_id'] = $tenantId;

        Pengeluaran::create($data);

        return back()->with('success', 'Pengeluaran berhasil tersimpan');
    }

    public function update(Request $request, $id)
    {
        $tenantId = auth()->user()->tenant_id;

        // Cari data berdasarkan ID DAN tenant_id (Proteksi Akses)
        $pengeluaran = Pengeluaran::where('tenant_id', $tenantId)->findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'akun_pengeluaran_id' => 'required|exists:akun_pengeluarans,id,tenant_id,' . $tenantId,
            'cabang_id' => 'required|exists:cabangs,id,tenant_id,' . $tenantId,
            'nominal' => 'required|numeric|min:0'
        ]);

        $pengeluaran->update($request->all());

        return redirect()->route('pengeluaran.index', [
            'tanggal' => $request->tanggal_filter,
            'cabang_id' => $request->cabang_filter
        ])->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        $tenantId = auth()->user()->tenant_id;

        // Pastikan hanya bisa hapus jika data itu miliknya
        $pengeluaran = Pengeluaran::where('tenant_id', $tenantId)->findOrFail($id);
        $pengeluaran->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}