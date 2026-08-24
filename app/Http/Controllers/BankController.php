<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BankController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;

        $banks = Bank::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            // Logika: Jika nama_bank adalah 'Kas', beri nilai 0, selain itu beri nilai 1.
            // Kemudian urutkan berdasarkan nilai tersebut (0 dulu baru 1), baru diikuti abjad.
            ->orderByRaw("CASE WHEN nama_bank = 'Kas' THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_bank', 'asc')
            ->get()
            ->map(function ($item) {
                $item->created_at_format = \Carbon\Carbon::parse($item->created_at)
                    ->locale('id')
                    ->translatedFormat('d F Y H:i');
                return $item;
            });

        return view('data_master.daftar_bank.index', compact('banks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string|max:255',
        ]);

        // Selalu sertakan tenant_id saat simpan data baru
        Bank::create([
            'nama_bank' => $request->nama_bank,
            'tenant_id' => auth()->user()->tenant_id,
        ]);

        return back()->with('success', 'Bank berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $bank = Bank::findOrFail($id);

        // PROTEKSI: Jangan izinkan edit data master (yang tenant_id-nya NULL)
        if (is_null($bank->tenant_id)) {
            return back()->with('error', 'Data master sistem tidak boleh diubah.');
        }

        // PROTEKSI: Pastikan hanya bisa edit milik sendiri
        if ($bank->tenant_id !== auth()->user()->tenant_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $request->validate([
            'nama_bank' => 'required|string|max:255',
        ]);

        $bank->update($request->only('nama_bank'));

        return back()->with('success', 'Bank berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);

        // PROTEKSI: Jangan izinkan hapus data master (seperti "Kas")
        if (is_null($bank->tenant_id)) {
            return back()->with('error', 'Data master sistem tidak boleh dihapus.');
        }

        // Ubah menjadi != (tidak pakai tiga sama dengan)
        if ($bank->tenant_id != auth()->user()->tenant_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $bank->delete();

        return back()->with('success', 'Bank berhasil dihapus.');
    }
}