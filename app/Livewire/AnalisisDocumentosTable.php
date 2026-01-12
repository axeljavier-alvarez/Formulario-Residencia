<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Solicitud;
use Carbon\Carbon;
use App\Models\Estado;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;

class AnalisisDocumentosTable extends DataTableComponent
{
    protected $model = Solicitud::class;

    // imprimir los errores
    // public ?string $errorObservaciones = null;

    // no mostrar cuando este en cancelado
    public function builder() : Builder
    {
        return Solicitud::query()->whereHas('estado', function($query){
            // $query->where('nombre', '!=', 'Cancelado');

            // agregar varios estados
            $query->whereNotIn('nombre', [
                'Cancelado',
                'En Proceso',
                'Completado'
            ]);
        });
    }

     public function configure(): void
{
    $this->setPrimaryKey('id');

    // quita el parpadeo
    $this->setLoadingPlaceholderStatus(false);

    $this->setThAttributes(function (Column $column) {
        return [
            'style' => 'background-color: #BFDBFE !important;',
            'class' => 'font-bold text-gray-900 text-center text-lg py-2',
        ];
        
    });

    $this->setTdAttributes(function(Column $column){
        return [
            'class' => match($column->getTitle()){
                'Estado' => 'text-center align-middle',
                'Acción' => 'text-center align-middle',
                default => 'text-left align-middle'
            }
        ];
    });

    $this->setTrAttributes(function($row, $index){
        return[
            'style' => $index % 2 === 0
             ? 'background-color: #FFFFFF' 
             : 'background-color: #F3F4F6'
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
                         'Pendiente' => '#F5725B',
                        'En proceso' => '#EAB308',
                        'Visita de Campo' => '#92400E',
                        'Completado' => '#22C55E',
                        'Cancelado' => '#EF4444'

                    };

                    return '<span style="color: ' . $color . '; font-weight: bold;">' . $value . '</span>';


                })

                ->html(),
        
                Column::make("Acción", "id")
                ->format(function($value, $row){

                    return '<button wire:click="verSolicitud('. $row->id . ')"
                    class="text-blue-600 underline font-bold hover:text-blue-800"> 
                    Ver Solicitud
                    </button>';
                })
                ->html()

        ];
    }


    public function verSolicitud($id)
    {

        $solicitud = Solicitud::with([
            'estado',
            'zona',
            'dependientes',
            'requisitosTramites.requisito',
            'requisitosTramites.tramite',
            
        ])->find($id);

        

        // llamar al array

        if($solicitud) {

            // traducir fecha de la solicitud

            $solicitud->fecha_registro_traducida = $solicitud->created_at 
            ? Carbon::parse($solicitud->created_at)
            ->translatedFormat('d F Y H:i') : 'N/A';

            // array de requisitos por tramite
            $solicitud->requisitos_por_tramite = $solicitud->requisitosTramites->map(function($rt){
                return $rt->requisito?->nombre;
            })->filter()->unique()->values()->toArray();

            // no mostrar  solicitudes con cancelado
            // $solicitud->bitacoras->each(function ($item){
            //     if(str_contains($item->evento, 'Cancelado')){
            //             $item->user = null;
            //         }

            // });
                   
            
            $this->dispatch('open-modal-solicitud', solicitud: $solicitud->toArray());
        }
    }


    #[On('peticionRechazar')]
public function rechazarSolicitud(int $id, string $observaciones)
{
    // validar observaciones
    // if (blank($observaciones)) {
    //     $this->errorObservaciones = 'Debe ingresar una observación';
    //     return;
    // }
    // $this->errorObservaciones = null;

        if (blank($observaciones)) {
            $this->dispatch('error-rechazo', mensaje: 'Debe ingresar una observación')
            ;
            return;
        }



    // $this->dispatch('error-rechazo', mensaje: 'Debe ingresar una observación');


    // estado cancelado
    $estadoCancelado = Estado::where('nombre', 'Cancelado')->first();
    if (!$estadoCancelado) return;

    // solicitud
    $solicitud = Solicitud::find($id);
    if (!$solicitud) return;

    // update
    $solicitud->update([
        'estado_id' => $estadoCancelado->id,
        'observaciones' => $observaciones,
    ]);

    // rechazo exitoso
    $this->dispatch('rechazo-exitoso');
    // $this->dispatch('rechazoExitoso');

    // refresh tabla
    /* 
    $this->dispatch('refreshDatatable');
    $this->dispatch('refreshComponent'); */
}



    // peticion en proceso
    #[On('peticionEnProceso')]

    public function procesarSolicitud($id)
    {
        $estadoEnProceso = Estado::where('nombre', 'En proceso')->first();

        if(!$estadoEnProceso) return;

        $solicitud = Solicitud::find($id);

        if($solicitud){
            $solicitud->update([
                'estado_id' =>  $estadoEnProceso->id
            ]);

            $this->dispatch('solicitud-aceptada');
                /* 
            $this->dispatch('refreshDatatable');
            $this->dispatch('refreshComponent'); */
        }
    }

    // mandar solicitud a campo
    #[On('peticionCampo')]
    public function visitaCampoSolicitud($id)
    {
        $estadoVisitaCampo = Estado::where('nombre', 'Visita de Campo')->first();
        if(!$estadoVisitaCampo) return;

        $solicitud = Solicitud::find($id);

        if($solicitud){
            $solicitud->update([
                'estado_id' => $estadoVisitaCampo->id
            ]);

            $this->dispatch('solicitud-visita-campo');
        }
    }

   


   
}
