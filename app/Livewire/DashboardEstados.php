<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Estado;

class DashboardEstados extends Component
{



// App/Livewire/DashboardEstados.php

public function render()
{
    $estados = Estado::withCount('solicitudes')->get();

    $labels = $estados->pluck('nombre')->toArray();
    $counts = $estados->pluck('solicitudes_count')->toArray();
    $colors = [];

    foreach ($labels as $nombre) {
        $colors[] = match($nombre) {
            'Pendiente'       => '#FACC15',
            'Visita asignada' => '#D97706',
            'Visita realizada' => '#8B5CF6',
            'Por autorizar'    => '#3B82F6',
            'Por emitir'       => '#06B6D4',
            'Completado'       => '#22C55E',
            'Cancelado'        => '#EF4444',
            default            => '#6B7280',
        };
    }

    // MANDAR EL EVENTO ANTES DEL RETURN
    $this->dispatch('updateChart', labels: $labels, series: $counts, colors: $colors);

    return view('livewire.dashboard-estados', [
        'estados' => $estados
    ]);
}





    
}
