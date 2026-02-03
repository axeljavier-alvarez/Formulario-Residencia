<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Estado;

class DashboardTarjetonesEstados extends Component
{
    public function render()
    {
        $estados = Estado::whereNotIn('nombre', 
        ['Visita asignada', 'Visita realizada'])
        ->withCount('solicitudes')
        ->get();
        
        return view('livewire.dashboard-tarjetones-estados', [
            'estados' => $estados
        ]);
    }
}
