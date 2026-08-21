<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // Di AuthenticatedSessionController@store
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ Cek soft delete
        if ($user->role !== 'developer' && $user->tenant && $user->tenant->trashed()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan. Hubungi customer service.');
        }

        // ✅ Cek suspended untuk role user → logout
        if ($user->role === 'user' && $user->tenant && $user->tenant->status_langganan === 'suspended') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Toko Anda sedang dinonaktifkan sementara.');
        }

        // ✅ Cek suspended & pending → arahkan ke dashboard.pending (untuk admin/super_admin)
        if ($user->tenant && in_array($user->tenant->status_langganan, ['pending', 'suspended'])) {
            return redirect()->route('dashboard.pending');
        }

        return match ($user->role) {
            'developer' => redirect()->intended(route('developer.dashboard')),
            'owner', 'admin', 'super_admin' => redirect()->intended(route('dashboard')),
            'user' => redirect()->intended(route('main')),
            default => redirect()->route('login')->with('error', 'Role tidak dikenali.'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            ActivityLog::log('logout', 'auth', Auth::user()->name . ' logout');
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}