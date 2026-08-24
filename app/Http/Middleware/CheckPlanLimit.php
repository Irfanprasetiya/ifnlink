<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPlanLimit
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->tenant) {
            return $next($request);
        }

        $tenant = $user->tenant;

        // Cek jika mencoba akses fitur premium
        if ($request->is('transaksi/create') || $request->is('transaksi/store')) {
            if (!$tenant->canAddTransaction()) {
                return back()->with('error', '⚠️ Paket Gratis hanya bisa 10 transaksi/hari. Upgrade ke PRO untuk unlimited!');
            }
        }

        if ($request->is('cabang/create') || $request->is('cabang/store')) {
            if (!$tenant->canAddCabang()) {
                return back()->with('error', '⚠️ Paket Gratis hanya 1 cabang. Upgrade ke PRO untuk tambah cabang!');
            }
        }

        if ($request->is('user/create') || $request->is('user/store')) {
            if (!$tenant->canAddUser()) {
                return back()->with('error', "⚠️ Kuota user sudah penuh ({$tenant->users()->count()}/{$tenant->max_user}). Upgrade paket untuk tambah user!");
            }
        }

        return $next($request);
    }
}