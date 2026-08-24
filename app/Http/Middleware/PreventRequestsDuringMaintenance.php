<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;
use Illuminate\Support\Facades\Auth;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     */
    protected $except = [
        //
    ];

    public function handle($request, \Closure $next)
    {
        // Role developer selalu boleh lewat
        if (Auth::check() && Auth::user()->role === 'developer') {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}