<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tramite;
use App\Models\Estado;


use function Ramsey\Uuid\v1;

class DashboardEstados extends Component
{



// App/Livewire/DashboardEstados.php

public function render()
{
    
   // $tramites = Estado::whereNotIn('nombre', ['Visita asignada', 'Visita realizada'])
   //              ->withCount('solicitudes')
   //              ->get();

   $tramites = Tramite::withCount('solicitudes')->get();
   $estadosTarjetones = 
   Estado::whereNotIn('nombre', ['Visita asignada', 'Visita realizada'])
   ->withCount('solicitudes')
   ->get();
   
   $tramitesGrafica = Tramite::withCount('solicitudes')->get();
   $labels = $tramitesGrafica->pluck('nombre')->toArray();
   $counts = $tramitesGrafica->pluck('solicitudes_count')->toArray();
   $colors = [];

   foreach($tramitesGrafica as $tramite){
        $colors[] = match($tramite->slug) {
         'magisterio' => '#3B82F6',
         'solicitar-dpi-al-registro-nacional-de-las-personas' => '#22C55E',
         'inscripcion-extemporanea-de-un-menor-de-edad-ante-el-registro-nacional-de-las-personas' => '#FACC15',
         'inscripcion-extemporanea-de-un-mayor-de-edad-ante-el-registro-nacional-de-las-personas' => '#F97316',
         'tramites-legales-en-materia-civil' => '#8B5CF6',
         'tramites-legales-en-materia-penal-si-una-persona-se-encuentra-privada-de-libertad' => '#EF4444',
         default => '#6B7280'
        };
   }



   
   $this->dispatch('updateChart', 
   labels: $labels, 
   series: $counts, 
   colors: $colors
   );   

   return view('livewire.dashboard-estados', [
    'estadosTarjetones' => $estadosTarjetones
   ]);
}





    
}
