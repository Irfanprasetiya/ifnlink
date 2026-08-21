<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        Log::info('=== PasswordResetLinkController@store CALLED ===');
        Log::info('Request data:', $request->all());

        $request->validate([
            'login' => 'required|string',
        ]);

        $login = trim($request->input('login'));
        Log::info('Login input: ' . $login);

        // Cari user berdasarkan username
        $user = User::where('username', $login)->first();
        Log::info('User found by username: ' . ($user ? 'YES - ' . $user->username : 'NO'));

        // Jika tidak ketemu, coba cari lewat email tenant
        if (!$user) {
            $tenant = Tenant::where('email', $login)->first();
            Log::info('Tenant found by email: ' . ($tenant ? 'YES - ' . $tenant->email : 'NO'));

            if ($tenant) {
                // ✅ Cari user owner dari tenant ini
                $user = User::where('tenant_id', $tenant->id_tenant)
                    ->where('role', 'owner')
                    ->first();

                // Jika tidak ada owner, ambil user pertama dari tenant
                if (!$user) {
                    $user = User::where('tenant_id', $tenant->id_tenant)->first();
                }

                Log::info('User found by tenant: ' . ($user ? 'YES - ' . $user->username : 'NO'));
            }
        }

        if (!$user) {
            Log::warning('User NOT FOUND for: ' . $login);
            return back()->with('error', 'Username atau email tidak terdaftar.');
        }

        // Generate token
        $token = Str::random(64);
        Log::info('Token generated: ' . $token);

        // Simpan token
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->username],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );
        Log::info('Token saved to database');

        // ✅ Buat link dengan username
        $resetLink = url('/reset-password/' . $token . '?username=' . urlencode($user->username));
        Log::info('Reset link: ' . $resetLink);

        // Development mode: tampilkan link
        if (app()->environment('local')) {
            Log::info('Local environment - showing link');

            return back()->with([
                'status' => 'Link reset berhasil dibuat!',
                'reset_link' => $resetLink,
                'user_info' => "User: {$user->name} (Username: {$user->username})",
            ]);
        }

        // Production: kirim email
        $tenant = Tenant::where('id_tenant', $user->tenant_id)->first();
        $email = $tenant->email ?? null;
        Log::info('Tenant found: ' . ($tenant ? 'YES - ' . $tenant->nama_toko : 'NO'));
        Log::info('Tenant email: ' . ($email ?? 'null'));

        if ($email) {
            try {
                \Mail::raw('Reset password link: ' . $resetLink, function ($message) use ($email) {
                    $message->to($email)->subject('Reset Password - Omzetly.id');
                });
                Log::info('Email sent to: ' . $email);
                return back()->with('status', 'Link reset password telah dikirim ke email.');
            } catch (\Exception $e) {
                Log::error('Email error: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengirim email.');
            }
        }

        return back()->with('error', 'Email tenant tidak ditemukan.');
    }
}