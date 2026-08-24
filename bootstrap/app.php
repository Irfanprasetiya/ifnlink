<?php

use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Gabung semua alias dalam satu tempat
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'guest.redirect' => \App\Http\Middleware\RedirectIfAuthenticatedByRole::class,
            'prevent-back' => \App\Http\Middleware\PreventBackHistory::class,
            'check.plan' => \App\Http\Middleware\CheckPlanLimit::class,
            'check.pending' => \App\Http\Middleware\CheckPendingTenant::class,
            'check.tenant.active' => \App\Http\Middleware\CheckTenantActive::class,
        ]);

        $middleware->replace(
            \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class
        );

        // Nonaktifkan CSRF untuk route Midtrans
        $middleware->validateCsrfTokens(except: [
            '/midtrans/notification',
	    '/deploy',
        ]);
    })

    // bootstrap/app.php
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            return redirect()->route('login')->with('error', 'Sesi telah berakhir. Silakan login kembali.');
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
