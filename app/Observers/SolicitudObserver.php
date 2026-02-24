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

            $estadoAnteriorId = $solicitud->getOriginal('estado_id');
            $estadoAnterior = Estado::find($estadoAnteriorId);
            $nombreEstadoAnterior = $estadoAnterior?->nombre;

            $nuevoEstado = Estado::find($solicitud->estado_id);
            $nombreEstadoNuevo  = $nuevoEstado?->nombre ?? 'DESCONOCIDO';



            $comentario = $solicitud->observacion_bitacora;

            $descripcion = $comentario ?: match (true) {

            // SOLICITUD DESPUES DE PASAR POR VISITA DE CAMPO, CASO ESPECIAL
            $nombreEstadoNuevo === 'Por autorizar'
            && $nombreEstadoAnterior === 'Analisis'
            => 'Documentación revisada y validada sin visita de campo',

            $nombreEstadoNuevo === 'Por autorizar'
            && $nombreEstadoAnterior === 'Visita realizada'
            => 'Documentación revisada y visita de campo aprobada',


            $nombreEstadoNuevo === 'Rechazado'
            && $nombreEstadoAnterior === 'Visita realizada'
            => 'Solicitud rechazada y no emitida',

                $nombreEstadoNuevo === 'Rechazado' => 'La solicitud se rechazo.',
                $nombreEstadoNuevo === 'Analisis' => 'La solicitud y los documentos estan siendo analizadados',
                $nombreEstadoNuevo === 'Visita asignada'  => 'La solicitud fue asignada a visita de campo.',
                $nombreEstadoNuevo === 'Visita realizada' => 'El visitador de campo no ingreso observaciones',
                
                // $nombreEstadoNuevo === 'Por autorizar'       => 'La solicitud fue aceptada y está lista para ser emitida',
                $nombreEstadoNuevo === 'Emitido'       => 'La solicitud fue emitida y enviada a firma',
                // $nombreEstadoNuevo === 'Por autorizar' => 'La solicitud está pendiente de autorizar',
                $nombreEstadoNuevo === 'Autorizado' => 'La solicitud autorizada y firmada',

                 $nombreEstadoNuevo === 'Previo' => 'La solicitud se envio a previo',

                default            => 'Cambio de estado.',
            };

            Bitacora::create([
                'solicitud_id' => $solicitud->id,
                'user_id' => Auth::id(),
                'evento' => 'CAMBIO DE ESTADO: ' . $nombreEstadoNuevo,
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
