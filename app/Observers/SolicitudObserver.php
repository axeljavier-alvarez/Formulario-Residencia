<?php

namespace App\Observers;

use App\Models\Solicitud;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use App\Models\Estado;

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
        // registrar bitacora si cambio el estado
         if (!$solicitud->wasChanged('estado_id')) {
                return;
            }


            $nuevoEstado = Estado::find($solicitud->estado_id);
            $nombreEstado = $nuevoEstado ? $nuevoEstado->nombre : 'DESCONOCIDO';

            

        
            
            $descripcion = match ($nombreEstado) {
                'Cancelado'        => 'La solicitud se canceló.',
                'En proceso'       => 'La solicitud está en proceso para análisis.',
                'Visita asignada'  => 'La solicitud fue asignada a visita de campo.',
                'Visita realizada' => 'El visitador de campo no ingreso observaciones',
                default            => 'Cambio de estado.',
            };

            Bitacora::create([
                'solicitud_id' => $solicitud->id,
                'user_id' => Auth::id(),
                'evento' => 'CAMBIO DE ESTADO: ' . $nombreEstado,
                'descripcion' => $descripcion
            ]);
        
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
