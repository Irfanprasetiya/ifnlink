<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        \Log::info('Forgot password request received', ['login' => $request->input('login')]);

        $request->validate([
            'login' => 'required|string',
        ]);

        $login = $request->input('login');

        // Cari user berdasarkan username
        $user = User::where('username', $login)->first();

        \Log::info('User search by username', [
            'login' => $login,
            'found' => $user ? 'yes' : 'no'
        ]);

        // Jika tidak ketemu, coba cari lewat email tenant
        if (!$user) {
            $tenant = Tenant::where('email', $login)->first();

            \Log::info('Tenant search by email', [
                'email' => $login,
                'found' => $tenant ? 'yes' : 'no'
            ]);

            if ($tenant) {
                $user = User::where('tenant_id', $tenant->id_tenant)
                    ->where('role', 'owner')
                    ->first();

                \Log::info('User search by tenant', [
                    'tenant_id' => $tenant->id_tenant,
                    'found' => $user ? 'yes' : 'no'
                ]);
            }
        }

        if (!$user) {
            \Log::warning('User not found for login: ' . $login);
            return back()->with('error', 'Username atau email tidak terdaftar.');
        }

        // Generate token
        $token = Str::random(64);

        // Simpan token
        try {
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->username],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            \Log::info('Token saved to database');
        } catch (\Exception $e) {
            \Log::error('Failed to save token: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan token. Error: ' . $e->getMessage());
        }

        $resetLink = route('password.reset', ['token' => $token, 'username' => $user->username]);

        \Log::info('Reset link generated: ' . $resetLink);

        // Development mode: tampilkan link
        if (app()->environment('local')) {
            return back()->with([
                'status' => 'Link reset berhasil dibuat!',
                'reset_link' => $resetLink,
                'user_info' => "User: {$user->name} (Username: {$user->username})",
            ]);
        }

        // Production: kirim email
        $tenant = Tenant::find($user->tenant_id);
        $email = $tenant->email ?? null;

        if ($email) {
            try {
                Mail::send('emails.reset-password', [
                    'user' => $user,
                    'tenant' => $tenant,
                    'resetLink' => $resetLink,
                ], function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('Reset Password - Omzetly.id');
                });

                return back()->with('status', 'Link reset password telah dikirim ke email terdaftar.');
            } catch (\Exception $e) {
                \Log::error('Email error: ' . $e->getMessage());
                return back()->with('error', 'Gagal mengirim email.');
            }
        }

        return back()->with('error', 'Email tenant tidak ditemukan. Hubungi CS.');
    }

    public function showResetForm(Request $request, $token)
    {
        \Log::info('Show reset form for token: ' . $token);

        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        \Log::info('Reset password attempt', [
            'username' => $request->input('username'),
            'token' => $request->input('token') ? 'exists' : 'empty'
        ]);

        $request->validate([
            'token' => 'required',
            'username' => 'required|exists:users,username',
            'password' => 'required|min:8|confirmed',
        ]);

        // Cek token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->username)
            ->first();

        if (!$resetToken) {
            \Log::warning('Reset token not found for: ' . $request->username);
            return back()->with('error', 'Token reset tidak valid.');
        }

        if (!Hash::check($request->token, $resetToken->token)) {
            \Log::warning('Token hash mismatch');
            return back()->with('error', 'Token reset tidak valid.');
        }

        // Cek expired (30 menit)
        $tokenCreatedAt = \Carbon\Carbon::parse($resetToken->created_at);
        if ($tokenCreatedAt->addMinutes(30) < now()) {
            DB::table('password_reset_tokens')
                ->where('email', $request->username)
                ->delete();

            return back()->with('error', 'Token reset sudah expired.');
        }

        // Update password
        $user = User::where('username', $request->username)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        \Log::info('Password updated for: ' . $user->username);

        // Hapus token
        DB::table('password_reset_tokens')
            ->where('email', $request->username)
            ->delete();

        return redirect()->route('login')
            ->with('status', 'Password berhasil direset. Silakan login.');
    }
}