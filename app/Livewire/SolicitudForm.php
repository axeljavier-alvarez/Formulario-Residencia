<?php

namespace App\Livewire;


use Livewire\Component;
use App\Models\Solicitud;
use App\Models\Zona;
use App\Models\Requisito;
use App\Models\Estado;
use App\Models\RequisitoTramite;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use App\Models\Tramite;


use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionSolicitud;
use App\Mail\NuevaSolicitudAdmin;

class SolicitudForm extends Component
{

    // campos del form
    public $no_solicitud;
    public $anio;
    public $nombres;
    public $apellidos;
    public $email;
    public $telefono;
    public $cui;
    public $domicilio;
    public $observaciones;

    public $zonas;
    public $zona_id;
    // luego mostrar toast de alpine
    // public $toast = null;


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

    // requisitos
    public $requisitos=[];


    // mostrar modal de exito
    // controla el model de solicitud enviada
    public $mostrarExito = false;
    // para mostrar el numero de solicitud
    public $ultimoNoSolicitud;

    // enmascarar email
    public $emailEnmascarado;


     public function mount()
    {
        $this->anio = now()->year;

        $this->zonas = Zona::all();

        // parte del tramite
        $this->tramites = Tramite::all();

    }

    public function rules (){

        return [
        'nombres' => 'required|string|max:60',
        'apellidos' => 'required|string|max:60',
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
            Rule::unique('solicitudes', 'cui'),

            // Regla de validación lógica del cui
            function ($attribute, $value, $fail){
                if(!$this->cuiEsValido($value)){
                    $fail('El cui no es válido según su estructura de dígito verificador
                    y códigos geográficos');
                }
            }
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

    // validación por país
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

    // teléfono completo
    $validated['telefono'] = '+' . $this->codigo_pais . $this->telefono;

    DB::beginTransaction();

    try {
        // crear solicitud
        $solicitud = Solicitud::create($validated);

        // generar no_solicitud
        $no_solicitud = $solicitud->id . '-' . $solicitud->anio;


        // actualizar la propiedad directamente en la solicitud
        $solicitud->update(['no_solicitud'=> $no_solicitud]);
        // lo guardo para mostrarlo en el modal
        $this->ultimoNoSolicitud=$no_solicitud;
        // establecer propiedad para alpine
        $this->mostrarExito=true;



        // OBTENER IDs DE requisito_tramite para el tramite seleccionado
        $requisitosTramiteIDs = RequisitoTramite::where('tramite_id', $this->tramite_id)
            ->pluck('id')
            ->toArray();

        // GUARDAR EN LA TABLA PIVOTE
        $solicitud->requisitosTramites()->sync($requisitosTramiteIDs);

        // ENVIAR CORREO AL EMAIL DEL USUARIO
        Mail::to($solicitud->email)
        ->send(new NotificacionSolicitud(
            "Tu solicitud con número {$solicitud->no_solicitud} fue registrada correctamente."
        ));

        // ENVIAR CORREO AL ADMINISTRADO
        Mail::to('axel5javier536@gmail.com')
        ->send(new NuevaSolicitudAdmin($solicitud));


        // MOSTRAR MENSAJE DE ALPINE
        // $this->mostrarExito = true;
        // $this->ultimoNoSolicitud = $solicitud->no_solicitud;

        DB::commit();

        // enmascarando el email
        $this->emailEnmascarado = $this->enmascararEmail($solicitud->email);

        // $this->resetExcept('anio');        
        $this->zonas = Zona::all();

        // $this->toast=[
        //     'type' => 'success',
        //     'message' => 'Solicitud enviada correctamente'
        // ];

    } catch(\Throwable $e){
        DB::rollBack();
        dd($e->getMessage());
    }
}





    public function render()
    {

        // quitar esto para probar el enviar
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
                    // $this->validate([
                    //     'nombres' => 'required|string|max:60',
                    //     'apellidos' => 'required|string|max:60',
                    //     'email' => [
                    //         'required',
                    //         'email',
                    //         'max:45',
                    //         Rule::unique('solicitudes', 'email')
                    //     ],

                    //     'telefono' => $this->reglasTelefonoPorPais(),

                    //     'codigo_pais' => 'required',
                    //     'cui' => [
                    //         'required',
                    //         'string',
                    //         'size:13',
                    //         Rule::unique('solicitudes', 'cui'),
                    //         // regla validacion cui
                    //         function ($attribute, $value, $fail){
                    //             if(!$this->cuiEsValido($value)){
                    //                 $fail('El CUI ingresado no es válido según su estructura.');
                    //             }
                    //         }
                    //     ],
                    //     'domicilio' => 'required|string|max:255',
                    //     'zona_id' => 'required|exists:zonas,id',
                    // ]);
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
                // $this->dispatch('validation-error');
                $this->setErrorBag($e->validator->errors());
                return false; // hay errores
            }
        }


        protected function reglasTelefonoPorPais()
{
    return [
        'required',
        function ($attribute, $value, $fail) {
            $codigo = $this->codigo_pais;

            if (isset($this->reglasTelefonos[$codigo])) {
                $longitudRequerida = $this->reglasTelefonos[$codigo];

                // quitar espacios, guiones, etc.
                $telefonoLimpio = preg_replace('/\D/', '', $value);

                if (strlen($telefonoLimpio) != $longitudRequerida) {
                    $fail("Este número debe tener {$longitudRequerida} dígitos.");
                }
            }
        }
    ];
}


public function updatedTramiteId($value)
{
    if ($value) {
        // buscar tramite
        $tramite = Tramite::with('requisitos')->find($value);
        // pone requisitios en propiedad publica $requisitos
        $this->requisitos = $tramite ? $tramite->requisitos->toArray() : [];
    } else {
        // si el usuario borra seleccion se borra lista
        $this->requisitos = [];
    }
}


public function verRequisitos()
{
    if (!$this->tramite_id) {
        dd("Debe seleccionar un trámite primero");
    }

    $tramite = Tramite::with('requisitos')->find($this->tramite_id);

    dd($tramite->requisitos);
}


// enmascarar email

public function enmascararEmail($email)
{
    // dividiendo el email
    [$usuario, $dominio] = explode('@', $email);

    // primeras 3 letras
    $primeras = substr($usuario, 0, 3);

    // mascara
    $mascara = str_repeat('*', max(strlen($usuario) -3, 0));

    return $primeras . $mascara . '@' . $dominio;


}

// resetear formulario al estar en el paso 1
public function resetFormulario()
{
    $this->reset([
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'codigo_pais',
        'cui',
        'domicilio',
        'observaciones',
        'zona_id',
        'tramite_id',
        'requisitos',
        'emailEnmascarado',
    ]);

    
}

// logica del CUI
private function cuiEsValido(string $cui): bool
{
   //1. Validar formato inicial
           $cui = preg_replace('/[^0-9]/', '', $cui);
  // 2. verificar que la cadena tenga 13 caracteres sino no deja
           if(strlen($cui) !== 13) {
            return false;
           }
    // 3. extraer partes
    // substr $cadena original, $posicion_inicial y $longitud a extraer;
    // primeros 8 digitos 
    $numero = substr($cui, 0, 8);
    // 9no digitio (Digito de control)
    $verificador = (int)substr($cui, 8, 1);
    // 10mo y 11mo digito (Código de departamento)
    $depto = (int) substr($cui, 9, 2);
    //12mo y 13mo digito (Codigo de municipio)
    $muni = (int) substr($cui, 11, 2);
 
    // 3. Validación de códigos de departamento y municipio
    // Array de municipios por departamento (índice 0 = depto 1, índice 21 = depto 22)

    $munisPorDepto = [17, 8, 16, 16, 13, 14, 19, 8, 
    24, 21, 9, 30, 32, 21, 8, 
    17, 14, 5, 11, 11, 7, 17];

    // verificar que el codigo departamentos este entre 1 y 22
    if($depto < 1 || $depto > count($munisPorDepto) ||
    // restarle 1 al depto para verificar sus municipios por ejemplo
    // 02-1 = 1 entonces 8 es el numero de municipios del departamento 02
    $muni < 1 || $muni > $munisPorDepto[$depto - 1]){
        return false; 
    }

    // 4. validación del digitio verificador (Módulo 11)
    $total = 0;
    for($i = 0; $i < 8; $i ++){
        $dig = (int)$numero[$i];
        // multiplicadores: 2,3,4,5,6,7,8,9
        $total += $dig * ($i + 2); 
    }

    $digitoCalculado = $total % 11;

    return $digitoCalculado === $verificador;
}

}
