<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Models\Cabang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        // Cek pending
        $user = auth()->user();
        if ($user->tenant && $user->tenant->status_langganan === 'pending') {
            session(['pending_tenant_id' => $user->tenant_id]);
            Auth::logout();
            return redirect()->route('checkout', $user->tenant->plan_id)
                ->with('info', 'Silakan selesaikan pembayaran terlebih dahulu.');
        }

        $tenantId = $user->tenant_id;

        // Users
        $users = User::with('cabang')
            ->where('tenant_id', $tenantId)
            ->get();

        // Cabang: ambil milik tenant ATAU data master (tenant_id NULL)
        $cabangs = Cabang::where(function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)
                ->orWhereNull('tenant_id');
        })
            ->orderByRaw("CASE WHEN tenant_id IS NULL THEN 0 ELSE 1 END ASC")
            ->orderBy('nama_cabang', 'asc')
            ->get();

        return view('users.index', compact('users', 'cabangs'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $tenantId = auth()->user()->tenant_id;
        $user = User::where('tenant_id', $tenantId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,user,super_admin',
            'cabang_id' => [
                'required',
                // Validasi: cabang_id harus ada di tenant ATAU data master
                function ($attribute, $value, $fail) use ($tenantId) {
                    $exists = Cabang::where('id', $value)
                        ->where(function ($query) use ($tenantId) {
                            $query->where('tenant_id', $tenantId)
                                ->orWhereNull('tenant_id');
                        })
                        ->exists();

                    if (!$exists) {
                        $fail('Cabang yang dipilih tidak valid.');
                    }
                },
            ],
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'cabang_id' => $request->cabang_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * ✅ Reset password user (Admin tenant)
     */
    public function resetPassword(Request $request, $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $tenantId = auth()->user()->tenant_id;
        $user = User::where('tenant_id', $tenantId)->findOrFail($id);

        // Generate password baru atau gunakan input
        $newPassword = $request->password ?? Str::random(10);

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        ActivityLog::log('reset_password', 'user', "Reset password user {$user->name}");

        return redirect()->route('users.index')->with('success', "Password untuk {$user->name} berhasil direset. Password baru: {$newPassword}");
    }

    public function destroy($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $tenantId = auth()->user()->tenant_id;
        $user = User::where('tenant_id', $tenantId)->findOrFail($id);
        $nama = $user->name;
        $user->delete();

        ActivityLog::log('delete', 'user', "Hapus user {$nama}");

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }

    public function getByCabang($id)
    {
        $tenantId = auth()->user()->tenant_id;

        $users = User::where('tenant_id', $tenantId)
            ->where('cabang_id', $id)
            ->get(['id', 'name']);

        return response()->json($users);
    }

}