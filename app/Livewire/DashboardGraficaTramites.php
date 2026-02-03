<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Tramite;

class DashboardGraficaTramites extends Component
{
    public function render()
    {
        $tramites = Tramite::withCount('solicitudes')->get();
        $labels = $tramites->pluck('nombre')->toArray();
        $counts = $tramites->pluck('solicitudes_count')->toArray();

        $colors = [];

        foreach($tramites as $tramite){
            $colors [] = match($tramite->slug){
                'magisterio' => '#3B82F6',
                'solicitar-dpi-al-registro-nacional-de-las-personas' => '#22C55E',
                'inscripcion-extemporanea-de-un-menor-de-edad-ante-el-registro-nacional-de-las-personas' => '#FACC15',
                'inscripcion-extemporanea-de-un-mayor-de-edad-ante-el-registro-nacional-de-las-personas' => '#F97316',
                'tramites-legales-en-materia-civil' => '#8B5CF6',
                'tramites-legales-en-materia-penal-si-una-persona-se-encuentra-privada-de-libertad' => '#EF4444',
                default => '#6B7280'
            };
        }

      // En DashboardGraficaTramites.php
       $this->dispatch('updateChart', 
            labels: $labels, 
            series: $counts, 
            colors: $colors
        );
        

     
        return view('livewire.dashboard-grafica-tramites', [
            'tramites' => $tramites
        ]);
    }
}
