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
    }

    public function columns(): array
    {
        return [
            
        // no solicitud
        Column::make("No solicitud", "no_solicitud")
                ->sortable(),
            
      
        // nombre completo nombres y apellidos
        Column::make("Nombre Completo", "nombres")
        ->sortable()
        ->searchable()
        ->format(fn($value, $row) => $row->nombres . ' ' . $row->apellidos),

        Column::make("Apellidos", "apellidos")
            ->hideIf(true),
        // email
        Column::make("Email", "email")
            ->sortable(),
        // mostrar telefono
        Column::make("Telefono", "telefono")
            ->sortable(),
            // Column::make("Cui", "cui")
            //     ->sortable(),
        // fecha de creacion   
        // Column::make("Fecha solicitud", "created_at")
        //         ->sortable(),

        Column::make("Fecha solicitud", "created_at")
        
        ->sortable()
        ->format(function($value, $row){
            return $row->created_at
            ? Carbon::parse($row->created_at)->translatedFormat('d F Y H:i')
            : '-';
        }),
        // estado
        Column::make("Estado", "estado.nombre") 
            ->sortable()
            ->searchable()
            


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

           

            
        ];
    }
}
