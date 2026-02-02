<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Estado;

use function Ramsey\Uuid\v1;

class DashboardEstados extends Component
{



// App/Livewire/DashboardEstados.php

public function render()
{
    
   $estados = Estado::whereNotIn('nombre', ['Visita asignada', 'Visita realizada'])
                ->withCount('solicitudes')
                ->get();

   $labels = $estados->pluck('nombre')->toArray();
   $counts = $estados->pluck('solicitudes_count')->toArray();
   $colors = [];

//    dd($labels);

   foreach($labels as $nombre) {
    $colors[] = match($nombre) {

            'Pendiente'       => '#FACC15',
            // 'Visita asignada' => '#D97706',
            // 'Visita realizada' => '#8B5CF6',
            'Por emitir'       => '#06B6D4',
            'Emitido'          => '#D6C19A', 
            'Por autorizar'    => '#3B82F6',
            'Autorizado'       => '#39FF14', 
            'Completado'       => '#16A34A', 
            'Previo'           => '#F97316',
            'Cancelado'        => '#EF4444',
            default            => '#6B7280',
            
    };
   }

   
   $this->dispatch('updateChart', labels: $labels, series: $counts, colors: $colors);   

   return view('livewire.dashboard-estados', [
    'estados' => $estados
   ]);
}





    
}
