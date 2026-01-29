<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Solicitud;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use App\Models\Estado;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EmisionConstanciasTable extends DataTableComponent
{
    
public $solicitudIdSeleccionada;


protected $model = Solicitud::class;

        public function builder(): Builder
        {
            return Solicitud::query()
                ->with(['estado', 'requisitosTramites.tramite'])
                ->whereHas('estado', function($query){
                    $query->whereIn('nombre', ['Por emitir', 'Completado']);
                })
                ->orderByDesc('id');
        }

        public function configure(): void
        {
            $this->setPrimaryKey('id');

            $this->setTableAttributes(['class' => 'border-separate border-spacing-y-3 px-4']);

            // ENCABEZADO
            $this->setThAttributes(fn()=>[
                'class' => 'bg-blue-600 text-white uppercase text-xs tracking-widest py-4 px-4 font-black
                border-none first:rounded-l-lg last:rounded-r-lg shadow-sm'
            ]);

            // PINTAR CELDAS 
            $this->setTdAttributes(function(Column $column){
                return [
                    'class' => match($column->getTitle()){
                        'Estado' => 'text-center align-middle',
                        'Acción' => 'text-center align-middle',
                        default => 'text-left align-middle'
                    }
                ];
            });

            // PINTAR FILAS 
            $this->setTrAttributes(function($row, $index) {
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
                    ->searchable()
                    ->format(function($value, $row) {
                        $tramite = $row->requisitosTramites->first()?->tramite?->nombre ?? 'Trámite General';
                        return "
                            <div class='flex flex-col'>
                                <span class='font-bold text-slate-800 text-sm'>{$row->nombres} {$row->apellidos}</span>
                                <div class='flex items-center gap-1 mt-1'>
                                    <span class='w-2 h-2 rounded-full bg-indigo-400'></span>
                                    <span class='text-[11px] font-bold text-indigo-600 uppercase'>{$tramite}</span>
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
                    'Pendiente'        => '#FACC15',
                    'Visita asignada'  => '#D97706',
                    'Visita realizada' => '#8B5CF6',
                    'Por autorizar'    => '#3B82F6',
                    'Por emitir'       => '#06B6D4',
                    'Completado'       => '#22C55E',
                    'Cancelado'        => '#EF4444',
                    default            => '#6B7280', 
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
                    ->label(fn($row) => "
                        <button wire:click='verDetalle({$row->id})' 
                                class='inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 text-xs font-bold rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm'>
                            <span>Ver Solicitud</span>
                            <svg class='w-4 h-4 ml-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                <path d='M15 12a3 3 0 11-6 0 3 3 0 016 0z' stroke-width='2'/>
                                <path d='M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z' stroke-width='2'/>
                            </svg>
                        </button>
                ")->html(),


                
            ];
        }




     // ABRIR MODAL
    public function verDetalle($id)
    {

    $this->solicitudIdSeleccionada = $id;

        //  $solicitud = Solicitud::find($id);



        // limitar registros bitacora 'bitacoras' => fn ($q) => $q->latest()->limit(10)
        // objeto estado en array
        $solicitud = Solicitud::with([
            'estado',
            'zona',
            // 'dependientes',
            // 'detalleSolicitud.dependiente',
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


                    // no mostrar  solicitudes con cancelado

                    // if(str_contains($item->evento, 'Cancelado')){
                    //     $item->user = null;
                    // }
            });

            $this->dispatch('open-modal-detalle', solicitud: $solicitud->toArray());
        }



    }


    // emitir constancia
    #[On('emitirConstancia')]
    public function emitirConstancia($id)
    {
        $estadoCompletado = Estado::where('nombre', 'Completado')->first();

        if(!$estadoCompletado) return;

        $solicitud = Solicitud::find($id);

        if($solicitud){
            $solicitud->update([
                'estado_id' => $estadoCompletado->id
            ]);

            $this->dispatch('solicitud-emitir-constancia');

        }
    }

  

    #[On('generar-constancia')]
public function generarConstancia()
{
    if (!$this->solicitudIdSeleccionada) {
        return;
    }

    $solicitud = Solicitud::with(['zona'])
        ->findOrFail($this->solicitudIdSeleccionada);

    $templatePath = resource_path('word/constancia_residencia.docx');
    $outputDir = storage_path('app/public/constancias');

    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    // Revisa que ya haya algun archivo generado con el mismo no_solicitud
    $pattern = $outputDir . '/' . $solicitud->no_solicitud . '*.docx';
    $archivosExistentes = glob($pattern);

    if (!empty($archivosExistentes)) {
        // Ya hay alguna constancia generada no_solicitud, no hacer nada
        return;
    }


    // Generar nombre único con hash
    $fileName = $solicitud->no_solicitud 
                . '-constancia-' 
                . Str::random(20) 
                . '.docx';

    $outputPath = $outputDir . '/' . $fileName;

    try {
        $template = new TemplateProcessor($templatePath);

        $template->setValue('nombre', $solicitud->nombres . ' ' . $solicitud->apellidos);
        $template->setValue('cui', $solicitud->cui ?? 'N/A');
        $template->setValue('domicilio', $solicitud->domicilio ?? 'N/A');
        $template->setValue('fecha', now()->format('d/m/Y'));

        $template->saveAs($outputPath);

        // guardar en detalle solicitud
        $solicitud->detalles()->create([
            'tipo' => 'constancia',
            'path' => 'constancias/' . $fileName,
            'user_id' => Auth::id(),

            
        ]);

        $this->dispatch('constancia-generada', [
            'fileName' => $fileName
        ]);

    } catch (\Exception $e) {
        return;
    }
}



}
