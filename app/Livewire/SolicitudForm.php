<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Zona;
use App\Models\Estado;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use App\Models\Tramite;


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


    /* para la parte del telefono */
    public $codigo_pais;

    /* validacion de numero para regla telefono */
    public $reglasTelefonos=[
        '502' => 8, // gt
        '503' => 8,  // salvador
        '504' => 8, // honduras
        '505' => 8, // nicaragua
        '506' => 8, // costa rica
        '52' => 10, // mexico
        '1' => 10 // estados unidos
    ];


    // tramite
    public $tramites;
    public $tramite_id;

    // pasos
    public $paso = 1;

     public function mount()
    {
        $this->anio = now()->year;

        $this->zonas = Zona::all();

        // parte del tramite
        $this->tramites = Tramite::all();
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
        'codigo_pais' => 'required',
        'cui' => [
            'required',
            'string',
            'size:13',
            Rule::unique('solicitudes', 'cui')
        ],
        'domicilio' => 'required|string|max:255',
        'observaciones' => 'nullable|string|max:255',
        'zona_id' => 'required|exists:zonas,id',
        'tramite_id' => 'required|exists:tramites,id'
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

        // validacion por pais
        $this->validate([
            'telefono' => [
                'required', function($attribute, $value, $fail){
                    $codigo = $this->codigo_pais;

                    if(isset($this->reglasTelefonos[$codigo])){
                        $longitudRequerida = $this->reglasTelefonos[$codigo];

                        if(strlen($value) != $longitudRequerida) {
                        $fail("Este número debe tener {$longitudRequerida} dígitos.");
                        }
                    }
                }
            ]
        ]);
        // telefono completo

        $validated['telefono'] = '+' . $this->codigo_pais . $this->telefono;

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

        $this->tramites = Tramite::all();
        $this->zonas = Zona::all();
        return view('livewire.solicitud-form');
    }

    // metodo confirmar para mostrar errores

    public function confirmar()
    {
        $this->validate();


        $this->validate([
            'telefono' => [
                'required', function($value, $fail){
                    $codigo = $this->codigo_pais;

                    if(isset($this->reglasTelefonos[$codigo])){
                        $longitudRequerida = $this->reglasTelefonos[$codigo];

                        if(strlen($value) != $longitudRequerida) {
                        $fail("Este número debe tener {$longitudRequerida} dígitos.");
                        }
                    }
                }
            ]
        ]);


        $this->dispatch('abrir-modal-confirmacion');
    }

    // validar paso
                public function validarPaso($paso)
        {
            try {
                if($paso == 1){
                    $this->validate([
                        'nombre' => 'required|string|max:60',
                        'apellido' => 'required|string|max:60',
                        'email' => 'required|email|max:45',
                        'telefono' => 'required|string',
                        'codigo_pais' => 'required',
                        'cui' => 'required|string|size:13',
                        'domicilio' => 'required|string|max:255',
                        'zona_id' => 'required|exists:zonas,id',
                    ]);
                }
                if($paso == 2){
                    $this->validate([
                        'tramite_id' => 'required|exists:tramites,id',
                    ]);
                }
                if($paso == 3){
                    $this->validate([
                        'observaciones' => 'nullable|string|max:255',
                    ]);
                }

                return true; // todo bien
            } catch (ValidationException $e) {
                $this->dispatch('validation-error'); 
                return false; // hay errores
            }
        }
}
