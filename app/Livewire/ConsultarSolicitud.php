<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Estado;

class ConsultarSolicitud extends Component
{
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