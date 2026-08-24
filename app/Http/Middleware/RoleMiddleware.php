<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles)) {
            return match ($user->role) {
                'developer' => redirect()->route('developer.dashboard'),
                'super_admin', 'admin', 'owner' => redirect()->route('dashboard'),
                'user' => redirect()->route('main'),
                default => redirect()->route('login')->with('error', 'Role tidak dikenali.'),
            };
        }

        return $next($request);
    }
}