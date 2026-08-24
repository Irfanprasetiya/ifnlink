<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\Plan;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('harga', 'asc')->get();
        return view('developer.paket.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'max_user' => 'nullable|integer|min:1',
            'fitur' => 'nullable|string',
        ]);

        $plan = Plan::create([
            'nama_paket' => $request->nama_paket,
            'slug' => Str::slug($request->nama_paket),
            'harga' => $request->harga,
            'max_user' => $request->max_user,
            'fitur' => $request->fitur ? array_map('trim', explode("\n", $request->fitur)) : [],
            'is_active' => true,
        ]);

        ActivityLog::log('create', 'paket', "Tambah paket {$plan->nama_paket}");

        return back()->with('success', 'Paket berhasil ditambahkan');
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'max_user' => 'nullable|integer|min:1',
            'fitur' => 'nullable|string',
        ]);

        $plan->update([
            'nama_paket' => $request->nama_paket,
            'slug' => Str::slug($request->nama_paket),
            'harga' => $request->harga,
            'max_user' => $request->max_user,
            'fitur' => $request->fitur ? array_map('trim', explode("\n", $request->fitur)) : [],
        ]);

        ActivityLog::log('update', 'paket', "Update paket {$plan->nama_paket}");

        return back()->with('success', 'Paket berhasil diperbarui');
    }

    public function toggleActive(Plan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);

        ActivityLog::log('toggle', 'paket', ($plan->is_active ? 'Aktifkan' : 'Nonaktifkan') . " paket {$plan->nama_paket}");

        return back()->with('success', $plan->is_active ? 'Paket diaktifkan' : 'Paket dinonaktifkan');
    }

    public function destroy(Plan $plan)
    {
        $nama = $plan->nama_paket;
        $plan->delete();

        ActivityLog::log('delete', 'paket', "Hapus paket {$nama}");

        return back()->with('success', 'Paket berhasil dihapus');
    }
}
