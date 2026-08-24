<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPendingTenant
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->tenant && $user->tenant->isLocked()) {
            // ✅ Route yang tetap bisa diakses saat locked (pending/suspended)
            $allowed = [
                'dashboard.pending',
                'pay.again',
                'status.langganan',
                'status.perpanjang',
                'status.upload-bukti',
                'checkout',
                'pay',
                'payment.finish',
                'logout',
                'profile.edit',
                'profile.update',
            ];

            $currentRoute = $request->route()?->getName();

            if (!in_array($currentRoute, $allowed)) {
                return redirect()->route('dashboard.pending');
            }
        }

        return $next($request);
    }
}