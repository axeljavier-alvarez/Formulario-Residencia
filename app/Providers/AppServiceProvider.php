<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Solicitud;
use App\Observers\SolicitudObserver;

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
     * OBSERVER CON FUNCIONES DE TRIGGER REGISTRADO
     */
    public function boot(): void
    {
        Solicitud::observe(SolicitudObserver::class);
    }
}
