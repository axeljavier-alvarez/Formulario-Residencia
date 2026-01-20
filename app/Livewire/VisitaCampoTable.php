<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Solicitud;
use App\Models\Bitacora;
use App\Models\DetalleSolicitud;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\Estado;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class VisitaCampoTable extends DataTableComponent
{

    // manejo de fotos
    use WithFileUploads;
    public array $fotos = [];

    protected $model = Solicitud::class;


    public string $estadoSeleccionado = 'Visita asignada';

    // reglas generales
    protected function rules()
    {
        return[
            'fotos.*' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],
        ];
    }
    // no mostrar cuando este en cancelado
    public function builder() : Builder
    {
        // return Solicitud::query()->whereHas('estado', function($query){
        //     // $query->where('nombre', '!=', 'Cancelado');

        //     // agregar varios estados
        //     $query->whereIn('nombre', [
        //         'Visita realizada',
        //         'Visita asignada',
        //     ]);
        // });

        return Solicitud::query()->whereHas('estado', function($query){
            $query->where('nombre', $this->estadoSeleccionado);
        });
    }

      #[On('filtrar-visitas')]
        public function filtrarVisitas(string $estado)
        {
            $this->estadoSeleccionado = $estado;

            $this->resetPage();
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
                      $color = match ($value) {
                        'Pendiente'        => '#FACC15',
                        'En proceso'       => '#3B82F6',
                        'Visita asignada'  =>  '#EAB308',
                        'Visita realizada'=> '#8B5CF6',
                        'Completado'       => '#22C55E',
                        'Cancelado'        => '#EF4444',
                        default            => '#6B7280',
                    };

                    return '<span style="color: ' . $color . '; font-weight: bold;">' . $value . '</span>';


                })

                ->html(),

                Column::make("Acción", "id")
                ->format(function($value, $row){

                    return '<button wire:click="verVisitaCampo('. $row->id . ')"
                    class="text-blue-600 underline font-bold hover:text-blue-800">
                    Verificar
                    </button>';
                })
                ->html()

        ];
    }




    public function verVisitaCampo($id)
    {


        //  $solicitud = Solicitud::find($id);



        // limitar registros bitacora 'bitacoras' => fn ($q) => $q->latest()->limit(10)
        // objeto estado en array
        $solicitud = Solicitud::with([
            'estado',
            'zona',
            'detalles.dependiente',
            'requisitosTramites.requisito',
            'requisitosTramites.tramite',
            'bitacoras.user',
            'detalles.user'
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


                    // no mostrar  solicitudes con cancelado

                    // if(str_contains($item->evento, 'Cancelado')){
                    //     $item->user = null;
                    // }
            });

            // procesar documentos
            $documentosNormales = $solicitud->requisitosTramites
            ->filter(fn($rt) => $rt->requisito?->slug !== 'cargas-familiares')
            ->map(function ($rt) use ($solicitud) {
                $detalle = $rt->detalles->where('solicitud_id', $solicitud->id)->first();
                if (!$detalle || !$detalle->path) return null;

                return [
                    'tipo' => 'normal',
                    'titulo' => $rt->reqeuisito?->nombre,
                    'path' => $detalle->path,
                ];
            })->filter();


            // buscar cargas familiares
            $rtCarga = $solicitud->requisitosTramites->where('requisito.slug', 'cargas-familiares')->first();
            $dependientes = collect();
            if($rtCarga){
                $dependientes = $rtCarga->detalles
                ->where('solicitud_id', $solicitud->id)
                ->load('dependiente')
                ->map(function ($d){
                    if (!$d->dependiente) return null;
                    return [
                        'id' => $d->id,
                        'nombre' => $d->dependiente->nombres . ' ' . $d->dependiente->apellidos,
                        'path' => $d->path ?? null
                        ];
                })->filter()->values();
            }

            // unificar documentos
            $documentosFinales = $documentosNormales->values()->all();
            $documentosFinales[] = [
                'tipo' => 'carga',
                'titulo' => 'Cargas familiares',
                'dependientes' => $dependientes->toArray()
            ];


            // convertir a array
           $solicitudArray = $solicitud->toArray();
           $solicitudArray['documentos'] = $documentosFinales;

           $solicitudArray['fotos'] = collect($solicitudArray['detalles'] ?? [])
            ->filter(function($detalle){
                return !empty($detalle['path']) && is_null($detalle['requisito_tramite_id']);
            })
           ->map(function($detalle){
            return [
                'id' => $detalle['id'],
                'ruta' => $detalle['path'],
                'visitador_campo' =>
                $detalle['user']['name'] ?? 'Sisitema'
                ];
           })
           ->values()
           ->all();

            // $this->dispatch('open-modal-visita', solicitud: $solicitud->toArray());

        $this->dispatch('open-modal-visita', solicitud: $solicitudArray);
        }



    }

    // aceptar visita de campo con observaciones
    #[On('visitaRealizada')]
    public function visitaRealizada(int $id, string $observaciones)
    {
        $solicitud = Solicitud::find($id);
        if(!$solicitud) return;

        $estadoVisitaRealizada = Estado::where('nombre', 'Visita realizada')->first();
        if(!$estadoVisitaRealizada) return;

        $solicitud->update([
            'estado_id' => $estadoVisitaRealizada->id,
        ]);

        // guardar bitacora
        // Bitacora::create([
        //     'solicitud_id' => $solicitud->id,
        //     'user_id' => Auth::id(),
        //     'evento' => 'CAMBIO DE ESTADO: Visita de campo realizada',
        //     'descripcion' => trim(strip_tags($observaciones)) ?: 'Visita de campo realizada sin observaciones.'
        // ]);



        // guardar fotos unaxuna
        // dd($this->fotos);
        foreach($this->fotos as $foto){



            $extension = $foto->getClientOriginalExtension();

            $hashAleatorio = Str::random(20);

            $nombreFinal = $solicitud->no_solicitud . '-' . $hashAleatorio . '.' . $extension;

            $ruta = $foto->storeAs(
                'visita-campo',
                $nombreFinal,
                'public'
            );

            DetalleSolicitud::create([
                'solicitud_id' => $solicitud->id,
                'user_id' => Auth::id(),
                'requisito_tramite_id' => null,
                'path' => $ruta,
                'tipo' => 'foto_visita'
            ]);
        }

        // limpiar fotos
        $this->reset('fotos');

        $this->dispatch('visita-realizada');
    }



}
