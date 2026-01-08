<?php

namespace App\Observers;

use App\Models\Solicitud;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class SolicitudObserver
{
    /**
     * Handle the Solicitud "created" event.
     */
    public function created(Solicitud $solicitud): void
    {
        Bitacora::create([
            'solicitud_id' => $solicitud->id,
            'user_id' => null,
            'evento' => 'CREACION',
            'descripcion' => 'Solicitud creada exitosamente desde el formulario.'
        ]);
    }

    /**
     * Handle the Solicitud "updated" event.
     */
    public function updated(Solicitud $solicitud): void
    {
        
    }

    /**
     * Handle the Solicitud "deleted" event.
     */
    public function deleted(Solicitud $solicitud): void
    {
        //
    }

    /**
     * Handle the Solicitud "restored" event.
     */
    public function restored(Solicitud $solicitud): void
    {
        //
    }

    /**
     * Handle the Solicitud "force deleted" event.
     */
    public function forceDeleted(Solicitud $solicitud): void
    {
        //
    }
}
