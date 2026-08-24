<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class UserRegisterController extends Controller
{
    /**
     * Tampilkan form registrasi dengan daftar cabang milik tenant yang login
     */
    public function create(): View
    {
        if (!auth()->check()) {
            abort(403);
        }

        $tenantId = auth()->user()->tenant_id;

        // Ambil cabang milik tenant ATAU data master (tenant_id NULL)
        $cabangs = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_cabang', 'asc')
            ->get();

        return view('auth.register', compact('cabangs'));
    }

    /**
     * Simpan data user baru dengan proteksi tenant_id
     */
    public function store(Request $request): RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();
        $tenantId = $user->tenant_id;

        // Jika tenant masih pending, redirect ke pembayaran
        if ($user->tenant && $user->tenant->status_langganan === 'pending') {
            session(['pending_tenant_id' => $tenantId]);
            Auth::logout();
            return redirect()->route('checkout', $user->tenant->plan_id)
                ->with('info', 'Silakan selesaikan pembayaran terlebih dahulu.');
        }

        if (!$tenantId) {
            return back()->with('error', 'Akun tidak terhubung ke tenant.');
        }

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            return back()->with('error', 'Tenant tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,user,super_admin',
            'cabang_id' => 'required|exists:cabangs,id',
        ]);


        $newUser = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'cabang_id' => $request->cabang_id,
            'tenant_id' => $tenantId,
        ]);

        ActivityLog::log('create', 'user', "Tambah user {$newUser->name} di {$tenant->nama_toko}");

        return redirect()->back()->with('success', 'User berhasil ditambahkan.');
    }
}