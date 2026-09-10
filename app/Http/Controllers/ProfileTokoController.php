<?php
// app/Http/Controllers/ProfileTokoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileTokoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        if (!$tenant) {
            abort(404, 'Data toko tidak ditemukan.');
        }

        return view('profile-toko.index', compact('tenant', 'user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email,' . $tenant->id_tenant . ',id_tenant',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // ✅ Update tenant
            $tenant->update([
                'nama_toko' => $request->nama_toko,
                'nama_pemilik' => $request->nama_pemilik,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ]);

            // ✅ Sinkronkan nama_pemilik ke user.name
            $user->update([
                'name' => $request->nama_pemilik,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile toko berhasil diupdate.',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $user = Auth::user();
        $tenant = $user->tenant;

        try {
            // Hapus logo lama
            if ($tenant->logo && Storage::disk('public')->exists($tenant->logo)) {
                Storage::disk('public')->delete($tenant->logo);
            }

            // Upload logo baru
            $path = $request->file('logo')->store('tenant-logos', 'public');
            $tenant->update(['logo' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Logo berhasil diupload.',
                'logo_url' => Storage::url($path),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}