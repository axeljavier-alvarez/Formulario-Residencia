<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Solicitud;
use Carbon\Carbon;
use App\Models\Estado;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Bitacora;

class AnalisisDocumentosTable extends DataTableComponent
{
    protected $model = Solicitud::class;
        public array $fotos = [];


    // imprimir los errores
    // public ?string $errorObservaciones = null;

    // no mostrar cuando este en cancelado


    public function builder(): Builder
    {
        // Cargando las relaciones
        return Solicitud::query()
            ->with(['estado', 'requisitosTramites.tramite'])
            ->whereHas('estado', function ($query) {
                $query->whereIn('nombre', ['Pendiente', 'Analisis', 'Visita asignada', 'Visita realizada']);
            })
            ->orderByDesc('id');
    }


    // personalizar buscaddor

     public function configure(): void
    {
       $this->setPrimaryKey('id');


       $this->setSearchFieldAttributes([
        'class' => 'rounded-xl border-slate-300 bg-white px-10 py-2.5 text-sm focus:border-blue-600 focus:ring-4 focus:ring-blue-100 shadow-sm transition-all w-full md:w-[450px]',
        'placeholder' => 'Escriba nombre, DPI o número de expediente...',
    ]);

       
              $this->setAdditionalSelects([
                'solicitudes.estado_id',
                'solicitudes.nombres', 
                'solicitudes.apellidos', 
                'solicitudes.cui',
                ]);

    // Diseño de la tabla con espacio entre filas
    $this->setTableAttributes(['class' => 'border-separate border-spacing-y-3 px-4']);

    // Títulos de la tabla (Encabezados)
    $this->setThAttributes(fn() => [

        'class' => 'bg-blue-600 text-white uppercase text-xs tracking-widest py-4 px-4 font-black border-none first:rounded-l-lg last:rounded-r-lg shadow-sm'
    ]);


    $this->setTdAttributes(function(Column $column){
        return [
            'class' => match($column->getTitle()){
                'Estado' => 'text-center align-middle',
                'Acción' => 'text-center align-middle',
                default => 'text-left align-middle'
            }
        ];
    });

    // Filas con borde lateral dinámico
    $this->setTrAttributes(function($row, $index){
        return [
            'style' => $index % 2 === 0
            ? 'background-color: #FFFFFF'
            : 'background-color: #F3F4F6'
        ];
    });

    }



      public function columns(): array
        {
            return [

            Column::make("Telefono", "telefono")->hideIf(true),

                Column::make('ID', 'id')->hideIf(true),

                Column::make("Solicitud", "no_solicitud")
                    ->format(fn($value) => "
                        <div class='flex flex-col'>
                            <span class='text-[10px] font-bold text-slate-400 uppercase tracking-tighter'>Expediente</span>
                            <span class='font-black text-blue-700 text-base'>#{$value}</span>
                        </div>
                    ")->html(),

               Column::make("Solicitante / Trámite", "nombres")
    ->searchable(function(Builder $query, $searchTerm) {
        // Dividimos la búsqueda por espacios (por si escriben "Juan Perez")
        $words = explode(' ', $searchTerm);
        
        $query->where(function($q) use ($words, $searchTerm) {
            foreach ($words as $word) {
                $q->where(function($inner) use ($word) {
                    $inner->where('solicitudes.nombres', 'like', '%' . $word . '%')
                          ->orWhere('solicitudes.apellidos', 'like', '%' . $word . '%')
                          ->orWhere('solicitudes.cui', 'like', '%' . $word . '%');
                });
            }
        });
    })
    ->format(function($value, $row) {
        // Usamos los datos del objeto $row directamente
        $nombres = $row->nombres ?? '';
        $apellidos = $row->apellidos ?? '';
        $cui = $row->cui ?? 'N/A';
        $tramite = $row->requisitosTramites->first()?->tramite?->nombre ?? 'Trámite General';
        
        return "
            <div class='flex flex-col gap-0.5'>
                <div class='font-black text-slate-800 text-sm uppercase leading-tight'>
                    {$nombres} {$apellidos}
                </div>
                <div class='flex items-center gap-1.5'>
                    <span class='text-[10px] font-bold text-slate-400 uppercase tracking-widest'>DPI:</span>
                    <span class='text-[11px] font-mono font-bold text-[#2563EB] bg-blue-50 px-1.5 rounded'>
                        {$cui}
                    </span>
                </div>
                <div class='flex items-center gap-1 mt-1'>
                    <span class='w-1.5 h-1.5 rounded-full bg-indigo-500'></span>
                    <span class='text-[10px] font-black text-indigo-600 uppercase tracking-tight'>
                        {$tramite}
                    </span>
                </div>
            </div>
        ";
    })->html(),

                Column::make("Información de Contacto", "email")
                    ->searchable()
                    ->format(function($value, $row) {
                        $email = $row->email ?? 'Sin correo';
                        $tel = $row->telefono ?? 'Sin teléfono';

                        return "
                            <div class='flex flex-col gap-1.5'>
                                <div class='flex items-center group'>
                                    <div class='w-6 h-6 flex items-center justify-center bg-blue-100 text-blue-600 rounded-md mr-2 shadow-sm'>
                                        <svg class='w-3.5 h-3.5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                            <path d='M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                        </svg>
                                    </div>
                                    <span class='text-xs font-medium text-slate-600'>{$email}</span>
                                </div>

                                <div class='flex items-center'>
                                    <div class='w-6 h-6 flex items-center justify-center bg-green-100 text-green-600 rounded-md mr-2 shadow-sm'>
                                        <svg class='w-3.5 h-3.5' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                            <path d='M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/>
                                        </svg>
                                    </div>
                                    <span class='text-xs font-bold text-slate-700'>{$tel}</span>
                                </div>
                            </div>
                        ";
                    })->html(),

                Column::make("Fecha Registro", "created_at")
                    ->format(fn($value) => "
                        <div class='bg-slate-50 p-2 rounded-lg border border-slate-100 w-fit'>
                            <span class='block text-xs font-bold text-slate-700'>" . Carbon::parse($value)->translatedFormat('d M, Y') . "</span>
                            <span class='block text-[10px] text-blue-500 font-medium'>" . Carbon::parse($value)->format('H:i A') . "</span>
                        </div>
                    ")->html(),




                    Column::make("Estado", "estado.nombre")
            ->format(function($value) {

                
                $color = match (trim($value)) {
                        'Pendiente'     => '#FACC15',
                         'Visita asignada'  => '#D97706',
                        'Visita realizada' => '#8B5CF6',
                        'Analisis'      => '#06B6D4', 
                        'Por autorizar' => '#3B82F6', 
                        'Emitido'       => '#A8A29E', 
                        'Autorizado'    => '#22C55E', 
                        'Previo'        => '#F97316',
                        'Rechazado'     => '#EF4444',
                        default         => '#6B7280',
                };

                $bgColor = $color . '26';

                return "
                    <span style='
                        background-color: {$bgColor};
                        color: {$color};
                        border: 1px solid {$color};
                        display: inline-block;
                        padding: 4px 12px;
                        border-radius: 9999px;
                        font-size: 10px;
                        font-weight: 900;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
                    '>
                        <span style='margin-right: 4px;'>●</span> {$value}
                    </span>
                ";
            })
            ->html(),

                
          Column::make("Acción")
    ->label(function($row) {
        // Obtenemos el nombre tal cual viene de la DB y quitamos espacios accidentales
        $estadoActual = trim($row->estado->nombre ?? '');

        // Comparación directa: Si el nombre es exactamente 'Pendiente'
        if ($estadoActual === 'Pendiente') {
            $textoBoton = "Analizar Expediente";
            $clasesBoton = "bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-600 hover:text-white shadow-emerald-100";            
            $metodoClick = "abrirExpediente({$row->id})";
        } else {
            // Para cualquier otro estado (Analisis, Visita asignada, etc.)
            $textoBoton = "Continuar revisión";
            $clasesBoton = "bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-600 hover:text-white";
            $metodoClick = "verSolicitud({$row->id})";
        }

        return "
            <button wire:click='{$metodoClick}'
                    class='inline-flex items-center px-4 py-2 text-xs font-bold rounded-xl transition-all duration-300 shadow-sm {$clasesBoton}'>
                <span>{$textoBoton}</span>
                <svg class='w-4 h-4 ml-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                    <path d='M15 12a3 3 0 11-6 0 3 3 0 016 0z' stroke-width='2'/>
                    <path d='M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' stroke-width='2'/>
                </svg>
            </button>
        ";
    })->html(),
            ];
        }



    public function verSolicitud($id)
    {
        $solicitud = Solicitud::with([
            'estado',
            'zona',
            'detalles.dependiente',
            'requisitosTramites.requisito',
            'requisitosTramites.tramite',
            'requisitosTramites.detalles',
            'bitacoras.user'
        ])->find($id);


        if ($solicitud) {
        $solicitud->fecha_registro_traducida = $solicitud->created_at
            ? Carbon::parse($solicitud->created_at)->translatedFormat('d F Y H:i')
            : 'N/A';

            // documentos de tipo normal
            $documentosNormales = $solicitud->requisitosTramites
            ->filter(fn($rt) => $rt->requisito?->slug !== 'cargas-familiares')
            ->map(function($rt) use ($solicitud){
                $detalle = $rt->detalles->where('solicitud_id', $solicitud->id)->first();


                if(!$detalle || !$detalle->path){
                    return null;
                }

                return [
                    'tipo' => 'normal',
                    'titulo' => $rt->requisito?->nombre,
                    'path' => $detalle->path
                ];
            })->filter();

            // cargas familiares
            $rtCarga = $solicitud->requisitosTramites->where('requisito.slug', 'cargas-familiares')
            ->first();

            $dependientes = collect();

            if($rtCarga){
                $dependientes = $rtCarga->detalles
                ->where('solicitud_id', $solicitud->id)
                ->load('dependiente')
                ->map(function ($d){
                    if(!$d->dependiente) return null;

                    return [
                        'id' => $d->id,
                        'nombre' => $d->dependiente->nombres . ' ' . $d->dependiente->apellidos,
                        'path' => $d->path ?? null
                    ];
                })->filter()->values();
            }

            // unificar ambos
            $arrayFinal = $documentosNormales->values()->toArray();

            $arrayFinal[] = [
                'tipo' => 'carga',
                'titulo' => 'Cargas familiares',
                'dependientes' => $dependientes->toArray()
            ];


            // poder ver la bitacora

            $solicitud->bitacoras->each(function ($item) {
                $item->fecha_formateada = Carbon::parse($item->created_at)
                    ->translatedFormat('d F Y H:i');
            });

            $solicitud->documentos = $arrayFinal;


            // parte de las fotos
            $fotos = $solicitud->detalles
            ->filter(function ($detalle) {
                return !empty($detalle->path)
                    && is_null($detalle->requisito_tramite_id);
            })
            ->map(function ($detalle) {
                return [
                    'id'         => $detalle->id,
                    'path'       => $detalle->path,
                    'visitador'  => optional($detalle->user)->name,
                    'fecha'      => $detalle->created_at
                        ? Carbon::parse($detalle->created_at)->translatedFormat('d F Y H:i')
                        : null,
                ];
            })
            ->values();

            $solicitud->fotos = $fotos;

            $this->dispatch('open-modal-solicitud', solicitud: $solicitud->toArray());
        }
    }


  #[On('peticionRechazar')]
public function rechazarSolicitud(int $id, string $descripcion)
{
    // validar observaciones
    if (blank($descripcion)) {
        $this->dispatch('error-rechazo', mensaje: 'Debe ingresar una observación');
        return;
    }

    // obtener estado "Cancelado"
    $estadoCancelado = Estado::where('nombre', 'Cancelado')->first();
    if (!$estadoCancelado) return;

    // obtener la solicitud
    $solicitud = Solicitud::find($id);
    if (!$solicitud) return;

    // actualizar solo el estado (no modificamos observaciones)
    // $solicitud->update([
    //     'estado_id' => $estadoCancelado->id,
    // ]);

    // // crear la bitácora directamente, igual que visitaRealizada
    // Bitacora::create([
    //     'solicitud_id' => $solicitud->id,
    //     'user_id' => Auth::id(),
    //     'evento' => 'CAMBIO DE ESTADO: Solicitud Rechazada',
    //     'descripcion' => trim(strip_tags($descripcion)) ?: 'Solicitud rechazada sin motivo detallado.'
    // ]);

    $solicitud->observacion_bitacora =
    trim(strip_tags($descripcion));

    $solicitud->estado_id = $estadoCancelado->id;
    $solicitud->save(['only', ['estado_id']]);

    // enviar evento al frontend
    $this->dispatch('rechazo-exitoso');
}





    // peticion en proceso
    #[On('peticionPorAutorizar')]

    public function solicitudPorAutorizar($id)
    {
        $estadoPorAutorizar = Estado::where('nombre', 'Por autorizar')->first();

        if(!$estadoPorAutorizar) return;

        $solicitud = Solicitud::find($id);

        if($solicitud){
            $solicitud->update([
                'estado_id' =>  $estadoPorAutorizar->id
            ]);

            $this->dispatch('solicitud-por-autorizar');
                /*
            $this->dispatch('refreshDatatable');
            $this->dispatch('refreshComponent'); */
        }
    }

    // mandar solicitud a campo
    #[On('peticionCampo')]
    public function visitaCampoSolicitud($id)
    {
        $estadoVisitaCampo = Estado::where('nombre', 'Visita asignada')->first();
        if(!$estadoVisitaCampo) return;

        $solicitud = Solicitud::find($id);

        if($solicitud){
            $solicitud->update([
                'estado_id' => $estadoVisitaCampo->id
            ]);

            $this->dispatch('solicitud-visita-campo');
        }
    }


    // abrir expediente
     public function abrirExpediente($id)
    {
        $solicitud = Solicitud::with(['estado', 'requisitosTramites.tramite'])->find($id);
        if (!$solicitud) return;

        if ($solicitud->estado?->nombre !== 'Pendiente') {
            $this->verSolicitud($id);
            return;
        }

        
        $this->dispatch('abrir-modal-expediente', solicitud: $solicitud->toArray());
    }

    // confirmar apertura
    #[On('ejecutar-confirmar-apertura')]
    public function confirmarApertura($id)
    {
        if(is_array($id)) { $id = $id['id'];}

        $estadoRevision = Estado::where('nombre', 'Analisis')->first();
        $solicitud = Solicitud::find($id);

        if($solicitud && $estadoRevision){
            $solicitud->update([
                'estado_id' => $estadoRevision->id
            ]);

             $this->dispatch('refreshDatatable');
            
            $this->dispatch('close-confirm'); 

            $this->verSolicitud($id);
        }
    }





}
