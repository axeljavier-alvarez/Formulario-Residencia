<?php


namespace App\Services;
use App\Models\Estado;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Storage;

// documentos y correciones
class PrevioService
{
    public function obtenerDocumentosPrevio($solicitud){
        if(!$solicitud) return [];

        $documentos = [];

        $ultimaBitacoraPrevio = $solicitud->bitacoras
        ->where('evento', 'CAMBIO DE ESTADO: Previo')
        ->last();

        if(!$ultimaBitacoraPrevio) return [];

        $observacion = $ultimaBitacoraPrevio->descripcion;

        foreach($solicitud->requisitosTramites as $rt){
            $nombreRequisito = $rt->requisito->nombre;

            if (str_contains(
                mb_strtolower($observacion),
                mb_strtolower($nombreRequisito)
            )) {
                $detalle = $solicitud->detalles()
                ->where('requisito_tramite_id', $rt->id)
                ->first();

                $documentos[] = [
                    'id_relacion' => $rt->id,
                    'nombre' => $nombreRequisito,
                    'detalle' => $detalle
                ];
            }
        }

        return $documentos;
    }


    /* guardar nuevos archivos, eliminar anteriores, actualizar detalles
    cambiar estado y registrar bitácora */
    public function procesarCorreccion($solicitud, $archivos)
    {
        // requisitos que fueron observados y necesitan correccion
        $documentos = $this->obtenerDocumentosPrevio($solicitud);
        // cuantos se actualizaron
        $archivosCargadosCount = 0;

        // recorrer archivos enviados desde Livewire
        foreach($archivos as $index => $archivoTemp) {
            // que exista archivo y documento en esa posición
            if($archivoTemp && isset($documentos[$index])){
                // obtener detalle relacionado
                $detalle = $documentos[$index]['detalle'];
                // guardar nuevo archivo
                $path = $archivoTemp->store('previos', 'public');

                // archivo anterior lo elimina
                if($detalle && $detalle->path &&
                Storage::disk('public')->exists($detalle->path)){
                    Storage::disk('public')->delete($detalle->path);
                }

                 // actualizar el detalle
                if($detalle) {
                    $detalle->update([
                        'path' => $path,
                        'user_id' => null,
                    ]);
                    // aumentar contador
                    $archivosCargadosCount++;
                }
            }
        }
        // cambiar estado si todo salio bien
        $estadoAnalisis = Estado::where('nombre', 'Analisis')->first();

        if($estadoAnalisis && $archivosCargadosCount > 0){
            // vuelve a analisis si cargo 1 archivo
            $solicitud->update([
                'estado_id' => $estadoAnalisis->id
            ]);


            // registrar en bitacora
            Bitacora::create([
                'solicitud_id' => $solicitud->id,
                'evento' => 'CORECCION DE PREVIO',
                'descripcion' => 'El ciudadano ha cargado ' . 
                $archivosCargadosCount .
                ' documentos solicitados. ',
                'fecha' => now(),
            ]);
        }
        // devolver cantidad
        return $archivosCargadosCount;

    }

}


//
