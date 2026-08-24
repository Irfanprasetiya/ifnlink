<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Menampilkan form register dengan daftar cabang.
     */
    public function create(): View
    {
        return view('auth.register', [
            'cabangs' => Cabang::all(), // ← Tambahkan ini
        ]);
    }

    /**
     * Proses simpan user baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:admin,user',
            'cabang_id' => 'required|exists:cabangs,id', // WAJIB PILIH CABANG
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'cabang_id' => $request->cabang_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect sesuai role
        return match ($user->role) {
            'admin' => redirect()->route('dashboard'),
            'user' => redirect()->route('main'),
            default => redirect(RouteServiceProvider::HOME),
        };
    }
}
