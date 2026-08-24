<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class NewPasswordController extends Controller
{
    public function create(Request $request, $token)
    {
        Log::info('=== NewPasswordController@create CALLED ===');
        Log::info('Token: ' . $token);
        Log::info('Username from request: ' . ($request->username ?? 'null'));
        Log::info('All request data:', $request->all());

        // ✅ Ambil username dari request
        $username = $request->username ?? $request->query('username') ?? null;
        $email = null;
        $namaToko = null;

        if ($username) {
            // Cari user berdasarkan username
            $user = User::where('username', $username)->first();
            Log::info('User found: ' . ($user ? 'YES - ID: ' . $user->id : 'NO'));

            if ($user) {
                Log::info('User tenant_id: ' . $user->tenant_id);

                // ✅ Cari tenant berdasarkan tenant_id user
                $tenant = Tenant::where('id_tenant', $user->tenant_id)->first();

                Log::info('Tenant found: ' . ($tenant ? 'YES - ID: ' . $tenant->id_tenant : 'NO'));

                if ($tenant) {
                    $email = $tenant->email;
                    $namaToko = $tenant->nama_toko;
                    Log::info('Tenant email: ' . ($email ?? 'null'));
                    Log::info('Tenant nama_toko: ' . ($namaToko ?? 'null'));
                } else {
                    Log::warning('Tenant NOT FOUND for tenant_id: ' . $user->tenant_id);
                }
            } else {
                Log::warning('User NOT FOUND for username: ' . $username);
            }
        } else {
            Log::warning('Username is EMPTY');
        }

        return view('auth.reset-password', compact('token', 'username', 'email', 'namaToko'));
    }

    public function store(Request $request)
    {
        Log::info('=== NewPasswordController@store CALLED ===');
        Log::info('Request data:', $request->all());

        $request->validate([
            'token' => 'required',
            'username' => 'required|exists:users,username',
            'password' => 'required|min:8|confirmed',
        ]);

        // Cek token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->username)
            ->first();

        if (!$resetToken || !Hash::check($request->token, $resetToken->token)) {
            Log::warning('Token invalid for: ' . $request->username);
            return back()->with('error', 'Token reset tidak valid.');
        }

        // Cek expired (30 menit)
        $tokenCreatedAt = \Carbon\Carbon::parse($resetToken->created_at);
        if ($tokenCreatedAt->addMinutes(30) < now()) {
            DB::table('password_reset_tokens')
                ->where('email', $request->username)
                ->delete();

            Log::warning('Token expired for: ' . $request->username);
            return back()->with('error', 'Token reset sudah expired.');
        }

        // Update password
        $user = User::where('username', $request->username)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Log::info('Password updated for: ' . $user->username);

        // Hapus token
        DB::table('password_reset_tokens')
            ->where('email', $request->username)
            ->delete();

        return redirect()->route('login')
            ->with('status', 'Password berhasil direset. Silakan login.');
    }
}