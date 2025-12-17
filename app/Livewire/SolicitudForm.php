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
use Livewire\WithFileUploads;


use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionSolicitud;
use App\Mail\NuevaSolicitudAdmin;
use App\Models\DetalleSolicitud;

use Illuminate\Support\Facades\Storage;
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

    // cargas familiares
    public $tieneCargasFamiliares = false;
    public $agregarCargas = null;

    // inicializar carga 1
    public $cargas = [];
    public $maxCargas= 4;

    // public $archivoCarga = [];

    public $archivoCarga;


    // para subir archivos
        use WithFileUploads;

      

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
    // Paso 1 - campos generales
    'nombres.required' => 'Debe ingresar los nombres.',
    'apellidos.required' => 'Debe ingresar los apellidos.',
    'email.required' => 'Debe ingresar el correo electrónico.',
    'email.email' => 'El correo electrónico no tiene un formato válido.',
    'email.unique' => 'Ya existe una solicitud con el correo :input.',
    'telefono.required' => 'Debe ingresar el teléfono.',
    'cui.required' => 'Debe ingresar el DPI.',
    'cui.size' => 'El DPI debe tener 13 caracteres.',
    'cui.unique' => 'Ya existe una solicitud con el DPI :input.',
    'domicilio.required' => 'Debe ingresar el domicilio.',
    'zona_id.required' => 'Debe seleccionar una zona.',
    'zona_id.exists' => 'La zona seleccionada no es válida.',

    // Paso 2 - trámites y requisitos
    'tramite_id.required' => 'Debe seleccionar un trámite.',
    'tramite_id.exists' => 'Debe seleccionar un trámite válido.',
    'requisitos.*.archivo.required' => 'Debe subir este requisito.',
    'requisitos.*.archivo.mimes' => 'Solo se permiten archivos PDF o JPG.',
    'requisitos.*.archivo.max' => 'El archivo no debe superar 2MB',

    
];




public function updated($property)
{
    if ($this->paso == 2) {
        $this->resetErrorBag($property);
    }
}



   public function submit()
{
    $validated = $this->validate($this->rules());

    $validated['anio'] = now()->year;
    $validated['estado_id'] = 1;

    // Validación por país
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

    $validated['telefono'] = '+' . $this->codigo_pais . $this->telefono;

    DB::beginTransaction();

    try {
        // CREAR SOLICITUD
        $solicitud = Solicitud::create($validated);

        // GENERAR NO_SOLICITUD
        $no_solicitud = $solicitud->id . '-' . $solicitud->anio;
        $solicitud->update(['no_solicitud' => $no_solicitud]);

        $this->ultimoNoSolicitud = $no_solicitud;
        $this->mostrarExito = true;

        // OBTENER IDs DE RequisitoTramite para el trámite seleccionado
        $requisitosTramiteIDs = RequisitoTramite::where('tramite_id', $this->tramite_id)
            ->pluck('id')
            ->toArray();

        // GUARDAR EN LA TABLA PIVOTE
        $solicitud->requisitosTramites()->sync($requisitosTramiteIDs);

        // SUBIR ARCHIVOS DE LOS REQUISITOS (NO CARGAS FAMILIARES)
        foreach($this->requisitos as $req){
            // no subieron nada saltar
            if(empty($req['archivo'])){
                continue;
            }

            // buscar el requisito_tramite correcto
            $requisitoTramite = RequisitoTramite::where('tramite_id', $this->tramite_id)
            ->where('requisito_id', $req['id'])
            ->first();

            if(!$requisitoTramite){
                continue;
            }

            // guardar archivo en nueva carpeta
            $path = $req['archivo']->store('requisitos_tramite', 'public');

            // registrar en detalle_solicitud
            DetalleSolicitud::create([
                'path' => $path,
                'solicitud_id' => $solicitud->id,
                'requisito_tramite_id' => $requisitoTramite->id
            ]);


        }
        

        // GUARDAR CARGAS FAMILIARES
if($this->agregarCargas === 'si' && count($this->cargas) > 0){
    foreach($this->cargas as $index => $carga) {
        // validar nombres y apellidos
        if(empty($carga['nombres']) || empty($carga['apellidos'])){
            continue;
        }

        $dependiente = $solicitud->dependientes()->create([
            'nombres' => $carga['nombres'],
            'apellidos'=> $carga['apellidos']
        ]);

        // Subir archivo de la carga si existe
        if(isset($carga['archivo']) && $carga['archivo']){
            $requisitoCarga = RequisitoTramite::where('tramite_id', $this->tramite_id)
                ->whereHas('requisito', function($q){
                    $q->where('slug', 'cargas-familiares');
                })->first();

            if($requisitoCarga){
                $path = $carga['archivo']->store('cargas_familiares', 'public');

                DetalleSolicitud::create([
                    'path' => $path,
                    'solicitud_id' => $solicitud->id,
                    'requisito_tramite_id' => $requisitoCarga->id
                ]);
            }
        }
    }
}



        // ENVIAR CORREO AL USUARIO
        if($solicitud->email){
            Mail::to($solicitud->email)
                ->send(new NotificacionSolicitud(
                    "Tu solicitud con número {$solicitud->no_solicitud} fue registrada correctamente."
                ));
        }

        // ENVIAR CORREO AL ADMIN
        Mail::to('axel5javier536@gmail.com')
            ->send(new NuevaSolicitudAdmin($solicitud));

        DB::commit();

        // ENMASCARAR EMAIL
        $this->emailEnmascarado = $this->enmascararEmail($solicitud->email);
        $this->zonas = Zona::all();

    } catch(\Throwable $e){
        DB::rollBack();
        // dd($e->getMessage()); // Solo mensaje
        // Mostrar error
        dd([
            'mensaje' => $e->getMessage(),
            'archivo' => isset($this->archivoCarga) ? $this->archivoCarga->getClientOriginalName() : null,
            'tramite_id' => $this->tramite_id,
            'cargas' => $this->cargas
        ]);
    }
}





    public function render()
    {

        // quitar esto para probar el enviar
        // $this->tramites = Tramite::all();
        // $this->zonas = Zona::all();

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
                    //                 $fail('El DPI ingresado no es válido');
                    //             }
                    //         }
                    //     ],
                    //     'domicilio' => 'required|string|max:255',
                    //     'zona_id' => 'required|exists:zonas,id',
                    // ]);
                }
                
                if ($paso == 2) {
                    
                    $rules = [];
                    $messages = [];
                    
                
                /* Validar trámite */
                $rules['tramite_id'] = 'required|exists:tramites,id';
                $messages['tramite_id.required'] = 'Debe seleccionar un trámite';
                $messages['tramite_id.exists'] = 'Debe seleccionar un trámite válido';
                
                /* requisitos que puedo omitir */
                foreach ($this->requisitos as $index => $req) {
                    if(
                        $req['slug'] === 'cargas-familiares' ||
                        $req['slug'] === 'fotocopia-del-boleto-de-ornato'
                    ){
                        continue;
                    }


                    /* validaciones de requisitos */
                $rules["requisitos.$index.archivo"] =
                            'required|file|mimes:pdf,jpg,jpeg|max:2048';
                $messages["requisitos.$index.archivo.required"] = 
                "Debe subir el requisito: {$req['nombre']}.";
                $messages["requisitos.$index.archivo.mimes"] =
                "Solo se permiten archivos PDF o JPG para {$req['nombre']}.";
                $messages["requisitos.$index.archivo.max"] =
                "El archivo {$req['nombre']} no debeddd superar 2MB.";         
               

                }

                 /* cargas familiares */
                if ($this->tieneCargasFamiliares) {

                    $rules['agregarCargas'] = 'required|in:si,no';
                    $messages['agregarCargas.required'] =
                    'Debe indicar si desea agregar cargas familiares.';
                }

                /* validaciones de cargas familiares */
                if($this->tieneCargasFamiliares && $this->agregarCargas === 'si'){
                    foreach($this->cargas as $index => $carga){
                        // ir contando
                        $num = $index + 1;

                        $rules["cargas.$index.nombres"] = 'required|string';
                        $rules["cargas.$index.apellidos"] = 'required|string';
                        $rules["cargas.$index.archivo"] =
                        'required|file|mimes:pdf,jpg,jpeg|max:2048';

                        $messages["cargas.$index.nombres.required"]=
                        "Debe ingresar los nombres de la carga familiar {$num}.";

                        $messages["cargas.$index.apellidos.required"] =
                        "Debe ingresar los apellidos de la carga familiar {$num}.";

                        $messages["cargas.$index.archivo.required"] =
                        "Debe subir el archivo de la carga familiar {$num}.";
                    }
                }


                

                    $this->validate($rules, $messages);
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


public function updatedCargas($value, $key)
{
    // $key vendrá con un formato tipo: "0.nombres" o "1.archivo"
    // Esto limpia el error de ese campo específico mientras el usuario escribe
    $this->resetErrorBag('cargas.' . $key);
}


public function updatedTramiteId($value)
{
    $this->resetErrorBag();
    $this->resetValidation();

    $this->resetErrorBag('tramite_id');
    $this->resetErrorBag('requisitos');
    $this->resetErrorBag('cargas');

    if ($value) {
        $tramite = Tramite::with('requisitos')->find($value);

        $this->requisitos = $tramite
            ? $tramite->requisitos->map(fn ($req) => [
                'id' => $req->id,
                'nombre' => $req->nombre,
                'slug' => $req->slug,
                'archivo' => null,
            ])->toArray()
            : [];

        $this->tieneCargasFamiliares = $tramite
            ? $tramite->requisitos->contains('slug', 'cargas-familiares')
            : false;
    } else {
        $this->requisitos = [];
        $this->tieneCargasFamiliares = false;
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
        //'codigo_pais',
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

// función para cargas
// inicializar carga
public function updatedAgregarCargas($value)
{
    // limpiar error de carga para agregar
    $this->resetErrorBag('agregarCargas');

    if ($value === 'si') {
        // carga de nombres apellidos y archivo
        $this->cargas = [
            [
                'nombres' => '',
                'apellidos' => '',
                'archivo' => null,
            ]
        ];
    } else {
        $this->cargas = [];
        $this->resetErrorBag('cargas');
    }
}



public function agregarCarga()
{
    if(count($this->cargas) < $this->maxCargas){
        $this->cargas[] = [
            'nombres' => '',
            'apellidos' => '',
            'archivo' => null
        ];
    }
}

public function eliminarCarga($index)
{
    // no borrar la carga 1
    if($index === 0) return;
    unset($this->cargas[$index]);
    $this->cargas = array_values($this->cargas);
}

// eliminar archivo requisito
public function eliminarArchivoRequisito($index)
{
    // limpiar
    $this->requisitos[$index]['archivo'] = null;
    // resetear en errores de validacion
    $this->resetErrorBag("requisitos.$index.archivo");

}

// eliminar archivo carga

public function eliminarArchivoCarga($index)
{
    if(isset($this->cargas[$index]['archivo'])){
        $this->cargas[$index]['archivo'] = null;
        $this->resetErrorBag("cargas.$index.archivo");
    }
}

public function updatedRequisitos($value, $key){
    if (!str_ends_with($key, '.archivo')) return;

    $parts = explode('.', $key);
    $index = $parts[0];
    $nombreRequisito = $this->requisitos[$index]['nombre'] ?? 'requisito';
        try {
            // 
            $this->validateOnly("requisitos.{$key}", [
            "requisitos.{$key}" => 'nullable|file|mimes:pdf,jpeg,jpg|max:2048'
        ], [
            "requisitos.{$key}.mimes" => "Para el requisito '{$nombreRequisito}' solo se permiten archivos PDF o JPG",
            "requisitos.{$key}" => $nombreRequisito
        ],
        [
            "requisitos.{$key}" => $nombreRequisito
        ]

        );

        } catch (\Exception $e) {
            
            // limpiar archivo invalido
            // $parts = explode('.', $key);
            // $index = $parts[0];
            
            $this->requisitos[$index]['archivo'] = null;
            throw $e;
        }
        
    
}



}
