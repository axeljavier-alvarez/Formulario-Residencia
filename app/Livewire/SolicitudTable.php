<?php

namespace App\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Solicitud;

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
            Column::make("Id", "id")
                ->sortable(),
            Column::make("No solicitud", "no_solicitud")
                ->sortable(),
            Column::make("Anio", "anio")
                ->sortable(),
            Column::make("Nombres", "nombres")
                ->sortable(),
            Column::make("Apellidos", "apellidos")
                ->sortable(),
            Column::make("Email", "email")
                ->sortable(),
            Column::make("Telefono", "telefono")
                ->sortable(),
            Column::make("Cui", "cui")
                ->sortable(),
            Column::make("Domicilio", "domicilio")
                ->sortable(),
            Column::make("Observaciones", "observaciones")
                ->sortable(),
            Column::make("Zona id", "zona_id")
                ->sortable(),
            Column::make("Estado id", "estado_id")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
