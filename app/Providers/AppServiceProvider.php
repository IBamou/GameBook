<?php

namespace App\Providers;

use App\Models\Reservation;
use App\Models\ReservationSession;
use App\Observers\ReservationObserver;
use App\Observers\ReservationSessionObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void {

        Gate::define('admin', function () {
            return auth()->check() && auth()->user()->role === 'admin';
        });

        Reservation::observe(ReservationObserver::class);
        ReservationSession::observe(ReservationSessionObserver::class);

    }
}
