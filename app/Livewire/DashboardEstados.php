<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Estado;

class DashboardEstados extends Component
{
    public function render()
    {
        $estados = Estado::withCount('solicitudes')->get();
        return view('livewire.dashboard-estados', compact('estados'));
    }
}
