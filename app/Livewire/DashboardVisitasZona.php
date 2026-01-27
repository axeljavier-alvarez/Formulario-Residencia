<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Zona;
use App\Models\Estado;

class DashboardVisitasZona extends Component
{
    public function render()
    {
        // Obtener IDs de los estados
        $estadoAsignada = Estado::where('nombre', 'Visita asignada')->first()->id;
        $estadoRealizada = Estado::where('nombre', 'Visita realizada')->first()->id;

        $zonas = Zona::withCount([
            'solicitudes as asignadas_count' => function ($q) use ($estadoAsignada) {
                $q->where('estado_id', $estadoAsignada);
            },
            'solicitudes as realizadas_count' => function ($q) use ($estadoRealizada) {
                $q->where('estado_id', $estadoRealizada);
            },
        ])->get();

        $labels = $zonas->pluck('nombre')->toArray();

        $series = [
            [
                'name' => 'Visita asignada',
                'data' => $zonas->pluck('asignadas_count')->toArray(),
            ],
            [
                'name' => 'Visita realizada',
                'data' => $zonas->pluck('realizadas_count')->toArray(),
            ],
        ];

        $this->dispatch('updateChartZonas', labels: $labels, series: $series);

        return view('livewire.dashboard-visitas-zona');
    }
}
