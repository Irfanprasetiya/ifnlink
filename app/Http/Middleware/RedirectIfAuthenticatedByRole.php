<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticatedByRole
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            return match (auth()->user()->role) {
                'developer' => redirect()->route('developer.dashboard'),
                'super_admin', 'admin' => redirect()->route('dashboard'),
                'user' => redirect()->route('main'),
                default => redirect('/'),
            };
        }

        return $next($request);
    }
}