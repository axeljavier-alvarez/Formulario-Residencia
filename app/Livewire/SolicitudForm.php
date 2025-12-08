<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Zona;
use App\Models\Estado;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class SolicitudForm extends Component
{

    // campos del form
    public $no_solicitud;
    public $anio;
    public $nombre;
    public $apellido;
    public $email;
    public $telefono;
    public $cui;
    public $domicilio;
    public $observaciones;

    public $zonas;
    public $zona_id;
    // luego mostrar toast de alpine
    public $toast = null;

     public function mount()
    {
        $this->anio = now()->year;

        $this->zonas = Zona::all();
    }

    public function rules (){

        return [
        'nombre' => 'required|string|max:60',
        'apellido' => 'required|string|max:60',
        'email' => [
            'required',
            'email',
            'max:45',
            Rule::unique('solicitudes', 'email')
        ],
        

        'telefono' => 'required|string|max:20',
        'cui' => [
            'required',
            'string',
            'size:13',
            Rule::unique('solicitudes', 'cui')
        ],
        'domicilio' => 'required|string|max:255',
        'observaciones' => 'nullable|string|max:255',
        'zona_id' => 'required|exists:zonas,id'
    ];
      
    }
        
   

    protected $messages = [
        'cui.size' => 'El cui debe tener 13 caracteres.',
        'email.email' => 'El email no tiene formato válido',
        'email.unique' => 'Ya existe una solicitud con el correo :input',
        'cui.unique' => 'Ya existe una solicitud con el cui :input'
    ];

   

    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);
    // }

    public function submit()
    {
        $validated = $this->validate($this->rules());

        $validated['anio'] = now()->year;

        // campo estado
        $validated['estado_id'] = 1;

        // dd($validated);

        DB::beginTransaction();
        try{
            $solicitud = Solicitud::create($validated);
            $solicitud->no_solicitud = $solicitud->id . '-' . $solicitud->anio;
            $solicitud->save();
            DB::commit();
            $this->resetExcept('anio');

            // cargar zonas despues de reset
            $this->zonas = Zona::all();
            $this->toast=[
                'type' => 'success',
                'message' => 'Solicitud enviada correctamente'
            ];
        }catch(\Throwable $e){
            DB::rollBack();

            dd($e->getMessage());
        }
    }



    public function render()
    {
        return view('livewire.solicitud-form');
    }
}
