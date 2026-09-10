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
        // ✅ 1. Authenticate dulu
        $request->authenticate();

        // ✅ 2. Regenerate session (WAJIB setelah auth berhasil - cegah session fixation)
        $request->session()->regenerate();

        $user = Auth::user();

        // ✅ 3. Cek soft delete tenant (sebelum redirect)
        if ($user->role !== 'developer' && $user->tenant && $user->tenant->trashed()) {
            return $this->logoutWithError($request, 'Akun Anda telah dinonaktifkan. Hubungi customer service.');
        }

        // ✅ 4. Cek suspended untuk role user
        if ($user->role === 'user' && $user->tenant && $user->tenant->status_langganan === 'suspended') {
            return $this->logoutWithError($request, 'Toko Anda sedang dinonaktifkan sementara.');
        }

        // ✅ 5. Cek pending & suspended untuk admin/super_admin/owner
        if ($user->tenant && in_array($user->tenant->status_langganan, ['pending', 'suspended'])) {
            return redirect()->route('dashboard.pending');
        }

        // ✅ 6. Redirect sesuai role
        return match ($user->role) {
            'developer' => redirect()->intended(route('developer.dashboard')),
            'owner', 'admin', 'super_admin' => redirect()->intended(route('dashboard')),
            'user' => redirect()->intended(route('main')),
            default => $this->logoutWithError($request, 'Role tidak dikenali.'),
        };
    }

    /**
     * ✅ Helper: Logout & redirect dengan error message
     * Mengurangi duplikasi kode logout
     */
    private function logoutWithError($request, string $message): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('error', $message);
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