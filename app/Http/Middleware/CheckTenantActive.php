<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTenantActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Abaikan developer
        if ($user && $user->role === 'developer') {
            return $next($request);
        }

        // Cek tenant untuk role lain
        if ($user && $user->tenant && $user->tenant->trashed()) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}