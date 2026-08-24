<?php

namespace App\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */




    public function boot()
    {
        // Set timezone PHP global
        date_default_timezone_set('Asia/Jakarta');

        // Set timezone Laravel (opsional tambahan untuk jaga-jaga)
        Config::set('app.timezone', 'Asia/Jakarta');

        View::composer('*', function ($view) {
            $isLocked = false;

            if (Auth::check() && Auth::user()->tenant) {
                $isLocked = Auth::user()->tenant->isLocked();
            }

            $view->with('isTenantLocked', $isLocked);
        });
    }


}
