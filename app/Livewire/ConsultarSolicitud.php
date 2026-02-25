<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Bitacora;
use App\Models\Estado;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
class ConsultarSolicitud extends Component
{
    use WithFileUploads;
    public $cui;
    public $no_solicitud;
    public $solicitud;
    public $estados; // Se llenará al cargar el componente
    public $error;

    // Se ejecuta una sola vez al cargar la página
    public function mount()
    {
        $this->estados = Estado::all();
    }

    public function consultar()
    {
        $this->reset(['solicitud', 'error']);

        if(empty($this->cui) || empty($this->no_solicitud)){
            $this->error = 'Debe completar ambos campos para poder consultar su solicitud.';
            return;
        }

        $this->validate([
            'cui' => 'required',
            'no_solicitud' => 'required'
        ]);

        // 1. CARGAMOS LAS RELACIONES (Añadimos bitacoras)
        $this->solicitud = Solicitud::with([
            'estado',
            'bitacoras', // IMPORTANTE para el mensaje de rechazo
            'requisitosTramites.tramite'
        ])
        ->where('cui', $this->cui)
        ->where('no_solicitud', $this->no_solicitud)
        ->first();

        if(!$this->solicitud){
            $this->error = 'Los datos ingresados no coinciden con ninguna solicitud.';
            return;
        }
    }


    public $archivos = []; 

#[Computed]
public function documentosPrevio()
{
    if (!$this->solicitud) return [];

    $documentos = [];
    $ultimaBitacoraPrevio = $this->solicitud->bitacoras
        ->where('evento', 'CAMBIO DE ESTADO: Previo')
        ->last();

    if (!$ultimaBitacoraPrevio) return [];
    
    $observacion = $ultimaBitacoraPrevio->descripcion;

    foreach ($this->solicitud->requisitosTramites as $rt) {
        $nombreRequisito = $rt->requisito->nombre;

        // Cambiamos a str_contains para evitar el error previo
        if (str_contains(mb_strtolower($observacion), mb_strtolower($nombreRequisito))) {            
            $detalle = $this->solicitud->detalles()
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

public function updatedArchivos()
{
    $this->validate([
        'archivos.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ]);
}

public function corregirPrevio()
{
    if (empty(array_filter($this->archivos))) {
        session()->flash('error_upload', 'Debe seleccionar al menos un archivo para cargar.');
        return;
    }
    // OJO: Aquí llamamos al método computado correctamente
    $documentos = $this->documentosPrevio(); 
    $archivosCargadosCount = 0;
    foreach ($this->archivos as $index => $archivoTemp) {
        if ($archivoTemp && isset($documentos[$index])) {
            $detalle = $documentos[$index]['detalle'];

            $path = $archivoTemp->store('previos', 'public');

            if ($detalle && $detalle->path && Storage::disk('public')->exists($detalle->path)) {
                Storage::disk('public')->delete($detalle->path);
            }

            if ($detalle) {
                $detalle->update([
                    'path' => $path,
                    'user_id' => null, 
                ]);
                $archivosCargadosCount++;
            }
        }
    }
    // Buscamos el estado Analisis (asegúrate que se escriba exactamente así en tu DB)
    $estadoAnalisis = Estado::where('nombre', 'Analisis')->first(); 
    
    if ($estadoAnalisis && $archivosCargadosCount > 0) {
        $this->solicitud->update(['estado_id' => $estadoAnalisis->id]);

        Bitacora::create([
            'solicitud_id' => $this->solicitud->id,
            'evento' => 'CORRECCIÓN DE PREVIO',
            'descripcion' => 'El ciudadano ha cargado ' . $archivosCargadosCount . ' documentos solicitados.',
            'fecha' => now(),
        ]);

        $this->reset('archivos');
        $this->consultar(); // Recarga la solicitud para que el modal se actualice
        session()->flash('success_upload', 'Documentos cargados exitosamente. Su solicitud ha pasado a Revisión.');
    }
}


    public function limpiar()
    {
        $this->reset(['cui', 'no_solicitud', 'error', 'solicitud']);
    }

    public function limpiarSolicitud()
    {
        $this->solicitud = null;
    }

    public function render()
    {
        return view('livewire.consultar-solicitud');
    }
}