<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Solicitud;
use Carbon\Carbon;

class SolicitudTable extends DataTableComponent
{
    protected $model = Solicitud::class;

   public function configure(): void
{
    $this->setPrimaryKey('id');

    $this->setThAttributes(function (Column $column) {
        return [
            'style' => 'background-color: #DBEAFE !important;',
            'class' => 'font-bold text-gray-900 text-center text-lg py-2',
        ];
        
    });

    $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
        return [
            'class' => 'text-left align-middle',
        ];
    });
}

    public function columns(): array
    {
        return [
            
            // no solicitud
            Column::make("No solicitud", "no_solicitud")
                    ->format(function($value){

                        return '<span class="font-bold text-gray-800">' 
                        . $value . 
                        '</span>';
                    })
                    ->html(),
                
        
            // nombre completo nombres y apellidos
            Column::make("Nombre Completo", "nombres")
            ->searchable()


            ->format(function($value, $row){
                // obtener el tramite
                $nombreTramite = $row->requisitosTramites->first()?->
                tramite?->nombre ?? 'N/A';

                // unificar nombres y apellidos en una sola variable

                $nombreCompleto = $row->nombres . ' ' . $row->apellidos;

                // html unificado
                return '<div class="flex flex-col">
                <span class="font-bold text-gray-800">
                ' . $nombreCompleto . ' 
                </span> 
                <span style="color: #322EA5; font-size: 0.85rem;"
                class="font-semibold">
                ' . $nombreTramite . '
                </span>
                </div>
                ';
            })
            ->html(),
            // ->format(fn($value, $row) => $row->nombres . ' ' . $row->apellidos),

           


            Column::make("Apellidos", "apellidos")
                ->hideIf(true),

            //  Column::make("Trámite")
            // ->label(fn($row) => $row->requisitosTramites->first()?->
            // tramite?->nombre ?? 'N/A'),

            // email
            Column::make("Email", "email")
                ,
            // mostrar telefono
            Column::make("Teléfono", "telefono")
                ,
                // Column::make("Cui", "cui")
                //     ->sortable(),
            // fecha de creacion   
            // Column::make("Fecha solicitud", "created_at")
            //         ->sortable(),

            Column::make("Fecha solicitud", "created_at")
            
            
            ->format(function($value, $row){
                return $row->created_at
                ? Carbon::parse($row->created_at)->translatedFormat('d F Y H:i')
                : '-';
            }),
            // estado
            Column::make("Estado", "estado.nombre") 
          
                


                ->format(function($value, $row){
                    $color = match($value) {
                        'Pendiente' => 'orange',
                        'En proceso' => '#EAB308',
                        'Completado' => 'green',
                        'Cancelado' => 'red'

                    };

                    return '<span style="color: ' . $color . '; font-weight: bold;">' . $value . '</span>';


                })

                ->html(),
         

         

        Column::make("Acción", "id")
        ->format(function($value, $row){
            // $url = route('interno.solicitudes.index', $row->id);

            // return ' <a href="' . $url . '"  class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold underline decoration-2 decoration-blue-200 hover:decoration-blue-800 transition-colors"> 
            // Ver detalle
            // </a>';

            return '<button wire:click="verDetalle('. $row->id . ')" 
            class="text-blue-600 underline font-bold hover:text-blue-800">
            Ver detalle
            </button>';
        })
        ->html(),

           

            
        ];
    }

    /* 
    
    item = {
  id: 12,
  evento: "Cambio de estado",
  descripcion: "Aprobado",
  created_at: "2026-01-08T14:32:10.000000Z",
  fecha_formateada: "08 enero 2026 14:32",
  user: {
    name: "Juan Pérez"
  }
}

*/

    // ABRIR MODAL
    public function verDetalle($id)
    {
        

        //  $solicitud = Solicitud::find($id);



        // limitar registros bitacora 'bitacoras' => fn ($q) => $q->latest()->limit(10)
        // objeto estado en array
        $solicitud = Solicitud::with([
            'estado', 
            'zona', 
            'dependientes',
            'requisitosTramites.tramite',
            'bitacoras.user'
            ])->find($id);

           // traduciendo la fecha de created_at
           
           

        if($solicitud){

            // traduciendo fecha de la solicitud
            $solicitud->fecha_registro_traducida = $solicitud->created_at
            ? Carbon::parse($solicitud->created_at)
            ->translatedFormat('d F Y H:i') : 'N/A';
            // traduciendo fecha de la bitacora
             $solicitud->bitacoras->each(function ($item) {
                $item->fecha_formateada = Carbon::parse($item->created_at)
                    ->translatedFormat('d F Y H:i');
            });

            $this->dispatch('open-modal-detalle', solicitud: $solicitud->toArray());
        }

        

    }



    
}
