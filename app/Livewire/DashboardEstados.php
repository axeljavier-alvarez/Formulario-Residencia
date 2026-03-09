<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Tramite;
use App\Models\Estado;



class DashboardEstados extends Component
{

public $estadosTarjetones = [];
public $labels = [];
public $counts = [];
public $colors = [];
public $icons = [];


public function mount(){
   $this->cargarDatos();
}

   public function cargarDatos()
   {
      // estados de los tarjetones
      $this->estadosTarjetones = Estado::whereNotIn('nombre', [
            'Visita asignada',
            'Visita realizada'
      ])
      ->withCount('solicitudes')
      ->get();

      $tramitesGrafica = Tramite::withCount('solicitudes')->get();

      $this->labels = $tramitesGrafica->map(function($t){
         return match($t->slug){
         'magisterio' => 'Magisterio',
                  'solicitar-dpi-al-registro-nacional-de-las-personas' => 'Solicitud DPI',
                  'inscripcion-extemporanea-de-un-menor-de-edad-ante-el-registro-nacional-de-las-personas' => 'Insc. Menor',
                  'inscripcion-extemporanea-de-un-mayor-de-edad-ante-el-registro-nacional-de-las-personas' => 'Insc. Mayor',
                  'tramites-legales-en-materia-civil' => 'Materia Civil',
                  'tramites-legales-en-materia-penal-si-una-persona-se-encuentra-privada-de-libertad' => 'Materia Penal',
                  default => substr($t->nombre, 0, 15) . '...',
         };
      })->toArray();

      $this->counts = $tramitesGrafica->pluck('solicitudes_count')->toArray();
      $this->colors = [];
      $this->icons=[];

      foreach($tramitesGrafica as $tramite){
         $this->colors[] = match($tramite->slug){
                  'solicitar-dpi-al-registro-nacional-de-las-personas' => '#22C55E',
                  'inscripcion-extemporanea-de-un-menor-de-edad-ante-el-registro-nacional-de-las-personas' => '#FACC15',
                  'inscripcion-extemporanea-de-un-mayor-de-edad-ante-el-registro-nacional-de-las-personas' => '#F97316',
                  'tramites-legales-en-materia-civil' => '#8B5CF6',
                  'tramites-legales-en-materia-penal-si-una-persona-se-encuentra-privada-de-libertad' => '#EF4444',
                  default => '#6B7280'
         };

         $this->icons[] = match($tramite->slug){
            'magisterio' => 'fas fa-chalkboard-teacher',
                  'solicitar-dpi-al-registro-nacional-de-las-personas' => 'fas fa-id-card',
                  'inscripcion-extemporanea-de-un-menor-de-edad-ante-el-registro-nacional-de-las-personas' => 'fas fa-child',
                  'inscripcion-extemporanea-de-un-mayor-de-edad-ante-el-registro-nacional-de-las-personas' => 'fas fa-user-plus',
                  'tramites-legales-en-materia-civil' => 'fas fa-balance-scale',
                  'tramites-legales-en-materia-penal-si-una-persona-se-encuentra-privada-de-libertad' => 'fas fa-gavel',
                  default => 'fas fa-file-alt'
         };
      }

      // actualizar gráfica
      $this->dispatch('updateChart',
      labels: $this->labels,
               series: $this->counts,
               colors: $this->colors,
               icons: $this->icons
      );
   }


  // refrescar tabla
   public function refreshData()
   {
      $this->cargarDatos();
   }

   // render dispara cada segundo
   public function render()
   {
      return view('livewire.dashboard-estados');

   }


    
}
