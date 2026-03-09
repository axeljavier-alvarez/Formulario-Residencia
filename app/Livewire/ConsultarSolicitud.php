<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Bitacora;
use App\Models\Estado;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use App\Services\PrevioService;

class ConsultarSolicitud extends Component
{
    use WithFileUploads;
    public $cui;
    public $no_solicitud;
    public $solicitud;
    public $estados; // Se llenará al cargar el componente
    public $error;
    public $archivos = []; 
    public $inputKey = 0;
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

        // observacion para que sea visible para consultar
        $previoService = app(PrevioService::class);
        $this->solicitud->observacion_previo = $previoService->obtenerObservacionPrevio($this->solicitud);
    }


public function updatedArchivos()
{
    $this->validate([
        'archivos.*' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
    ], [
        'archivos.*.mimes' => 'Solo se permiten archivos PDF o JPG.',
        'archivos.*.required' => 'Debe seleccionar un archivo.',
        'archivos.*.max' => 'El archivo no debe pesar más de 2MB.',
    ]);
}

#[Computed]
public function documentosPrevio()
{
    if (!$this->solicitud) {
        return [];
    }

    $service = app(PrevioService::class);

    return $service->obtenerDocumentosPrevio($this->solicitud);
}

public function corregirPrevio(PrevioService $service)
{
    // if(empty(array_filter($this->archivos))){
    //     session()->flash('error_upload', 'Debe seleccionar al menos un archivo para cargar');
    //     return;
    // }

    $documentos = $this->documentosPrevio;
    $archivosValidos = array_filter($this->archivos);

    if (count($archivosValidos) !== count($documentos)){
        session()->flash(
            'error_upload',
            'Debe cargar todos los documentos solicitados'
        );
        return;
    }

    $this->validate([
        'archivos.*' => 'required|file|mimes:pdf,jpg,jpeg|max:2048',
    ], [
        'archivos.*.mimes' => 'Solo se permiten archivos PDF o JPG.',
        'archivos.*.max' => 'El archivo no debe pesar más de 2MB.',
        'archivos.*.required' => 'Debe cargar todos los documentos solicitados.',
    ]);

// llamar al service una vez pasada la validación
    $cantidad = $service->procesarCorreccion(
        $this->solicitud,
        $this->archivos
    );

    // service guardo archivos resetea inputs, vuelve a consultar y muestra mensaje exito
    if ($cantidad > 0){
        $this->reset('archivos');
        $this->consultar(); 
        session()->flash('success_upload', 'Documentos cargados exitosamente');
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

    // eliminar archivo
    public function eliminarArchivo($index)
{
    unset($this->archivos[$index]);

    // fuerza re-render del input
    $this->inputKey++;
}
}