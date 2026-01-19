{{-- intl-tel-input --}}
<div class="px-4 md:px-8">

    <div
x-data="{
paso: 1,
{{-- toast: @entangle('toast'), --}}
mostrarConfirmacion: false,
{{-- eliminararchivorequisito --}}
mostrarConfirmacionEliminarRequisito: false,

mostrarConfirmacionEliminar: false,
archivoAEliminar: null,

mensaje: '',

confirmarEnvio() {
   this.mostrarConfirmacion = true;
},

{{-- eliminararchivorequisito --}}
abrirModalEliminarCarga(archivoIndex){
    this.archivoAEliminar = archivoIndex;
    this.mostrarConfirmacionEliminar = true;
},
abrirModalEliminarRequisito(archivoIndex){
    this.archivoAEliminar = archivoIndex;
    this.mostrarConfirmacionEliminarRequisito = true;
},

eliminarArchivo(){
$wire.eliminarArchivoRequisito(this.archivoAEliminar);
this.mostrarConfirmacionEliminarRequisito = false;
this.archivoAEliminar = null
},

eliminarArchivoCarga() {
    $wire.eliminarArchivoCarga(this.archivoAEliminar); 
    this.mostrarConfirmacionEliminar = false;
    this.archivoAEliminar = null;
},

siguientePaso(){
this.paso++;
},
pasoAnterior(){
this.paso--;
}
}"
 x-on:abrir-modal-eliminar-carga.window="abrirModalEliminarCarga($event.detail)"
x-on:abrir-modal-eliminar-requisito.window="abrirModalEliminarRequisito($event.detail)"
x-on:abrir-modal-confirmacion.window="mostrarConfirmacion = true"

class="max-w-4xl mx-auto my-20 bg-white border rounded-xl p-8 shadow-[0_0_10px_#EAEAEA]"

>

<img src="{{ asset('imagenes/icono_muni.png') }}" 
     alt="Icono" 
     class="w-20 md:w-32 mx-auto block">


  <h1 class="
                                tracking-widest 
                                text-2xl md:text-3xl 
                                text-[#030EA7] 
                                text-center 
                                mx-auto
                                px-16
                                whitespace-normal md:whitespace-nowrap">
                                        CONSTANCIA DE RESIDENCIA 
                                </h1>



    
<p class="mt-3 text-[#4B5563] text-base text-center md:text-lg font-bold" >
                            Complete la información requerida para registrar su solicitud
        </p>




    <!-- Indicadores de pasos -->
<div class="flex justify-center gap-4 my-6">

    <!-- Paso 1 -->
    <div
        @click="paso = 1"
        class="w-8 h-8 rounded-full cursor-pointer flex items-center justify-center border-2"
        :class="paso === 1 ? 'bg-[#83BD3F;] text-white' : 'bg-white text-black border-[#83BD3F;]'"
    >
        1
    </div>

    <!-- Paso 2 -->
        <div
            @click="
                $wire.validarPaso(1)
                    .then(valid => {
                        if (valid) {
                            paso = 2;
                        }
                    })
            "
            class="w-8 h-8 rounded-full cursor-pointer flex items-center justify-center border-2"
            :class="paso === 2 ? 'bg-[#83BD3F;] text-white' : 'bg-white text-black border-[#83BD3F;]'"
        >
            2
        </div>


    <!-- Paso 3 -->
    <div
        @click="
            $wire.validarPaso(2).then(valid => {
                if (valid) paso = 3;
            })
        "
        class="w-8 h-8 rounded-full cursor-pointer flex items-center justify-center border-2"
        :class="paso === 3 ? 'bg-[#83BD3F;] text-white': 'bg-white text-black border-[#83BD3F;]'"
    >
        3
    </div>

</div>


{{--
    <template x-if="toast">
        <div>
            <x-toast x-bind:type="toast.type">
                <span x-text="toast.message">

                </span>
            </x-toast>
        </div>
    </template> --}}


            @if ($errors->any())
    <div class="mb-4 p-4 rounded-md bg-[#F2DEDE] font-bold border border-[#A94442]">
        <h3 class="font-bold text-[#A94442]">Error</h3>

        <ul class="mt-2 list-disc list-inside text-[#A94442]">
            @foreach ($errors->getMessages() as $field => $messages)
                @foreach ($messages as $message)
                    <li>
                        <!-- Inicializar variable para almacenar -->
                        @php
                            $nombre = null;
                        @endphp

                        <!-- Comprobar el error -->
                        @if($message === 'validation.max.file')
                           @if (str_starts_with($field, 'requisitos.'))
                           @php 
                           // tomar posicion 2 del array, sino sera null
                           $index = explode('.', $field)[1] ?? null;
                           // tomar el nombre del requisito
                           $nombre = $requisitos[$index]['nombre'] ?? 'Requisito';
                           @endphp                               

                           @elseif (str_starts_with($field, 'cargas.'))
                           @php
                            $index = explode('.', $field)[1] ?? null;
                            $nombre = 'Carga familiar ' . ($index + 1);

                               
                           @endphp
                           @endif

                           @if($nombre)
                           <strong>{{ $nombre }}:</strong>
                           El archivo no debe superar 2MB.
                           @else
                           El archivo no debe superar 2MB.
                           @endif
                        @else 
                        {{ $message }}
                        @endif
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>
@endif


        <!-- Modal de confirmar eliminacion de archivo -->
        <div
        x-show="mostrarConfirmacionEliminarRequisito"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
        >

        <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg space-y-4">
            <h2 class="text-xl font-bold text-[#03192B]">
                Confirmar envío
            </h2>
            <p class="text-[#03192B]">
                ¿Está seguro de que desea eliminar este archivo?
            </p>

            

            <div class="flex justify-end gap-3 mt-4">
                <button @click="mostrarConfirmacionEliminarRequisito = false"
                class="px-4 py-2 rounded bg-gray-200 text-[#03192B] hover:bg-gray-300" 
                >
                Cancelar
                </button>
                <button @click="eliminarArchivo()"
                class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700"
                >
                Eliminar
                </button>


               
            </div>
        </div>

        </div>


        <!-- Modal eliminacion de archivo carga -->

        <div
            x-show="mostrarConfirmacionEliminar"
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
            x-cloak
        >
            <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg space-y-4">
                <h2 class="text-xl font-bold text-[#03192B]">Confirmar eliminación</h2>
                <p class="text-[#03192B]">¿Está seguro de que desea eliminar este archivo de carga familiar?</p>
                <div class="flex justify-end gap-3 mt-4">
                    <button @click="mostrarConfirmacionEliminar = false"
                        class="px-4 py-2 rounded bg-gray-200 text-[#03192B] hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button @click="eliminarArchivoCarga()"
                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>



        <!-- Modal de error de archivo -->
           




        <!-- Modal de Confirmacion -->

        <div
        x-show="mostrarConfirmacion"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
        s-transition
        >

        <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg space-y-4">
            <h2 class="text-xl font-bold text-[#03192B]">
                Confirmar envío
            </h2>
            <p class="text-[#03192B]"> ¿Esta seguro de que desea enviar la solicitud?
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Por favor revise sus datos antes de continuar
            </p>


            <div class="flex justify-end gap-3 mt-4">

                <!-- Cancelar (gris oscuro como Atrás) -->
                <button @click="mostrarConfirmacion = false" class="px-4 py-2 rounded bg-gray-200 text-[#03192B] hover:bg-gray-300" >
                    Cancelar
                </button>

                <!-- Enviar (negro como Siguiente) -->
                <button
                @click="
                    $wire.submit().then(() => {
                        mostrarConfirmacion = false;
                        {{-- $wire.set('mostrarExito', true) --}}
                    })
                "
                class="px-4 py-2 rounded bg-black text-white hover:bg-gray-800"
            >
                Enviar
                </button>


            </div>


        </div>

        </div>


       <!-- Modal de Éxito -->
        <!-- Modal de Éxito -->

       <div
    x-data="{ open: false }"
    x-init="$watch('$wire.mostrarExito', value => open = value)"
    x-show="open"
    x-cloak
    x-transition
    class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
>


            <div class="bg-white p-6 rounded-xl w-full max-w-md shadow-lg text-center">

                <h2 class="text-xl font-bold text-green-700 mb-2">¡Solicitud enviada correctamente!</h2>

                <p class="mb-4">

                    Su número de solicitud es: <strong>{{ $ultimoNoSolicitud }}</strong>

                </p>

                <p class="mb-4">
                    Se envió un correo a: <strong>{{ $emailEnmascarado }}</strong>
                </p>

                <button

                    @click="
                    $wire.resetFormulario();
                    $wire.set('mostrarExito', false);
                    paso = 1;
                    $dispatch('form-reset');
                    "

                    class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800"

                >
                    Cerrar

                </button>

            </div>

        </div>






    <!-- FORM (wire:submit.prevent) -->
   {{-- <form @submit.prevent="confirmarEnvio" class="space-y-4" enctype="multipart/form-data"> --}}


     <form wire:submit.prevent="confirmar" class="space-y-4" enctype="multipart/form-data">



            {{-- <x-validation-errors /> --}}
        {{-- <div>
            <x-label class="mb-1">Año</x-label>
            <x-input type="text" wire:model="anio" class="border rounded px-3 py-2 w-full" readonly />
        </div> --}}


<!--  FORM DE 2 INPUTS POR FILA -->
    <!-- Paso 1 -->
    <div x-show="paso === 1">

           <p class="mb-5 text-red-600 text-center text-sm mt-1 bg-yellow-100 p-2 rounded">
                    Ingrese los nombres y apellidos tal como aparecen en el DPI
            </p>

        


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div x-data="{ valor: '' }"  x-on:form-reset.window="valor = ''">
                <x-label class="mb-1 font-bold text-[#03192B]">
                    Nombres
                            <span class="text-red-600" x-show="valor === ''">*</span>

                </x-label>
                <x-input type="text"
                placeholder="Ingrese sus nombres"
                wire:model.live="nombres"
                x-model="valor"
                class="placeholder-[#797775] border rounded px-3 py-2 w-full" />
                {{-- @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror --}}
            </div>

            <div x-data="{ valor: '' }" x-on:form-reset.window="valor = ''">
                <x-label class="mb-1 font-bold text-[#03192B]">
                    Apellidos
                    <span class="text-red-600" x-show="valor === ''">*</span>
                </x-label>
                <x-input type="text"
                placeholder="Ingrese sus apellidos"
                wire:model.live="apellidos"
                x-model="valor"
                class="placeholder-[#797775] border rounded px-3 py-2 w-full" />
            </div>

            <div x-data="{ valor: '' }" x-on:form-reset.window="valor = ''">
                <x-label class="mb-1 font-bold text-[#03192B]">
                    Email
                    <span class="text-red-600" x-show="valor === ''">*</span>
                </x-label>
                <x-input type="email"
                placeholder="Ingrese su email"
                wire:model.live="email"
                x-model="valor"
                class="placeholder-[#797775] border rounded px-3 py-2 w-full" />

            </div>

            {{-- <div>
                <x-label class="mb-1 font-bold text-[#03192B]">Teléfono</x-label>
                <x-input type="number" placeholder="Ingresa tu número telefónico" wire:model.defer="telefono" class="placeholder-[#797775] border rounded px-3 py-2 w-full" />
            </div> --}}
            <div class="col-span-1 md:col-span-1" x-data="{ valor: ''}" x-on:form-reset.window="valor = ''" x-init="
                const input = document.querySelector('#telefono');
                const iti = window.intlTelInput(input, {
                    {{-- initialCountry: 'gt', --}}
                    onlyCountries: ['gt'],
                    separateDialCode: true,
                    {{-- preferredCountries: ['gt', 'mx', 'us', 'sv', 'hn'], --}}
                });

                $wire.set('codigo_pais', iti.getSelectedCountryData().dialCode);

                input.addEventListener('countrychange', () => {
                    $wire.set('codigo_pais', iti.getSelectedCountryData().dialCode);
                });
                " wire:ignore>
                <x-label>
                    Teléfono
                    <span class="text-red-600" x-show="valor === ''">*</span>
                </x-label>
                <input
                    id="telefono"
                    type="text"
                    class="border rounded px-3 py-2 w-full box-border"
                    placeholder="Ingrese su número"
                    x-model="valor"
                    {{-- x-on:input="$wire.set('telefono', $event.target.value)" --}}
                    x-on:input="
                    valor = $event.target.value.replace(/\D/g, '').slice(0, 8);
                    $wire.set('telefono', valor);
                    "
                    maxlength="8"
                />
            </div>


            <div x-data="{ valor: ''}" x-on:form-reset.window="valor = ''">
                <x-label class="mb-1 font-bold text-[#03192B]">
                    DPI
                    <span class="text-red-600" x-show="valor === ''">*</span>
                </x-label>
                <x-input type="text"
                placeholder="Ingrese su dpi"
                wire:model.live="cui"
                class="placeholder-[#797775] border rounded px-3 py-2 w-full"
                x-model="valor"
                maxlength="13"
                x-on:input="$event.target.value = $event.target.value.replace(/[^0-9]/g, '')" />
            </div>

            <div x-data="{ valor: ''}" x-on:form-reset.window="valor = ''">
                <x-label class="mb-1 font-bold text-[#03192B]">
                    Zona
                    <span class="text-red-600" x-show="valor === ''">*</span>
                </x-label>

                <select
                wire:model.live="zona_id"
                class="border rounded px-3 py-2 w-full"
                x-model="valor"
                >
                <option value="">
                    Seleccione una zona
                </option>

                @foreach ($zonas as $zona )
                <option value="{{ $zona->id }}">
                    {{ $zona->nombre }}
                </option>

                @endforeach
                </select>
            </div>
        </div>


        <div x-data="{ valor: ''}" x-on:form-reset.window="valor = ''">
            <x-label class="mb-1 mt-3 xl font-bold text-[#03192B]">
                Domicilio
            <span class="text-red-600" x-show="valor === ''">*</span>
            </x-label>
            <x-input type="text"
            placeholder="Ingrese la dirección de su domicilio"
            wire:model.live="domicilio"
            class="placeholder-[#797775] border rounded px-3 py-2 w-full"
            x-model="valor" />
        </div>

        {{-- <button type="button"
        @click="$wire.validarPaso(1).then(valid => valid ? siguientePaso() : null)"
        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-bl">
            Siguiente
        </button> --}}

        <div class="mt-4 flex justify-end">
            <button type="button"
                @click="$wire.validarPaso(1).then(valid => valid ? siguientePaso() : null)"
                class="px-4 py-2 bg-[black] hover:bg-gray-800 text-white rounded">
                Siguiente
            </button>
        </div>



    </div>

    <!-- Paso 2 -->
    {{-- <div x-show="paso === 2" wire:key="paso-2"> --}}

    <div x-show="paso === 2">

        <div x-data="{ valor: '' }">

            <x-label class="mb-1 font-bold text-[#03192B]">
                Trámite
                <span class="text-red-600" x-show="valor === ''">*</span>
            </x-label>

            <select
            wire:model.live="tramite_id"
            class="border rounded px-3 py-2 w-full"
            x-model="valor">
            <option value="">
                Seleccione un trámite
            </option>
                @foreach ($tramites as $tramite)
                    <option
                    value="{{ $tramite->id }}">
                    {{ $tramite->nombre }}
                    </option>
                @endforeach
            </select>
        </div>



            <!-- Requisitos por tramite -->
             @if(!empty($requisitos) && count($requisitos) > 0)
   @if ($tramites->firstWhere('id', $tramite_id)?->slug 
                !== 'tramites-legales-en-materia-penal-si-una-persona-se-encuentra-privada-de-libertad')
                <!-- titulo centrado -->
                <h2 class="text-center text-2xl font-bold mt-6 mb-2" style="color:#10069F">
                    REQUISITOS
                </h2>

                <p class="text-center text-sm p-2 rounded" style="background-color: #EFF6FF; color: #1E293B;">
                    Recuerde que puede subir únicamente documentos PDF o JPG
                </p>



                
{{--
                <div class="mt-4" wire:key="reqs-{{ $tramite_id }}">
                <ul class="list-disc list-inside text-[#03192B]">
                    @foreach($requisitos as $requisito)
                        <li>{{ $requisito['nombre'] }}</li>
                    @endforeach
                </ul>
                </div> --}}
                

             
                    
                <div class="overflow-x-auto mt-4 border-t-4 border-b-4" style="border-color:#83BD3F;">
                    <table class="w-full table-fixed text-left">
                        <thead>
                            <!-- md table-row se arregla -->
                            <tr class="border-b-4 hidden md:table-row" style="border-color:#83BD3F;">
                                <th class="px-4 py-3 font-bold text-[#03192B]">Requisitos</th>
                                <th class="px-4 py-3 font-bold text-[#03192B] text-center">Acción</th>
                            </tr>
                        </thead>

                    <tbody>
                        @foreach($requisitos as $index => $requisito)
                            @if($requisito['slug'] && $requisito['slug'] !== 'cargas-familiares')
                                                        <!-- md table-row se arregla -->
                            <tr class="border-b-2 flex flex-col md:table-row" style="border-color:#83BD3F;">
                                
                                <!-- celda nombre movil -->
                                <td class="px-4 py-3 block md:table-cell">
                                    <span class="text-[#03192B] font-medium md:font-normal">
                                        {{ $requisito['nombre'] }}
                                        @if($requisito['slug'] === 'fotocopia-del-boleto-de-ornato')
                                            <strong> (opcional)</strong>
                                        @endif
                                    </span>
                                </td>

                            <!-- celda movil acción -->         
                            <td class="px-4 py-3 block md:table-cell text-left md:text-right">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-end gap-2 md:gap-3">

        @if(!isset($requisitos[$index]['archivo']))
        <label class="flex md:inline-flex w-1/2 md:w-auto items-center gap-2 bg-[#10069F] text-white px-4 py-2 rounded hover:bg-[#0d057f] transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M4 12l7-8m0 0l7 8m-7-8v12" />
            </svg>
            <span>Subir archivo</span>
            <input type="file" wire:model="requisitos.{{ $index }}.archivo" accept="application/pdf,image/jpeg" class="hidden">
        </label>
        @else

        <!-- mensaje de elimianr alineandolo-->
        <div class="flex items-center justify-between md:justify-end w-full gap-3">
        <p class="text-[#10069F] text-sm truncate w-[200px] text-right" title="{{ $requisitos[$index]['archivo']->getClientOriginalName() }}">
            {{ $requisitos[$index]['archivo']->getClientOriginalName() }}
        </p>

          <button type="button"

                @click="abrirModalEliminarRequisito({{ $index }})"

                class="text-red-600 hover:text-red-800 transition-colors flex-shrink-0">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M9 6v12m6-12v12M5 6l1 14h12l1-14" />
            </svg>


        </button>
        </div>
        
        @endif

    </div>
</td>

                            </tr>
                            @endif
                        @endforeach
                    </tbody>


                </table>



                </div>
                @endif
                

                    {{-- @if(
                        $tramites->firstWhere('id', $tramite_id)?->slug
                        === 'tramites-legales-en-materia-penal-si-una-persona-se-encuentra-privada-de-libertad'
                    )                
                    <!-- tramite legal en materia penal -->
                    <div x-data="{ edad: '' }" class="mb-4 text-center">
                        <p class="mt-2 mb-2 font-semibold text-[#03192B]">Seleccione su edad</p>

                        <div class="flex justify-center gap-6 mb-2">
                            <label class="flex items-center gap-1">
                                <input type="radio" x-model="edad" value="menor"> Menor de edad
                            </label>

                            <label class="flex items-center gap-1">
                                <input type="radio" x-model="edad" value="mayor"> Mayor de edad
                            </label>
                        </div>

                        <p class="text-sm text-red-600 font-semibold" x-text="edad === 'menor' ? 'Adolescentes en conflicto con la ley penal' : (edad === 'mayor' ? 'Privados de libertad por encontrarse en un proceso penal' : '')"></p>
                    </div>  
                         @endif
                    --}}



                    @if (
                        $tramites->firstWhere('id', $tramite_id)?->slug
                        === 'tramites-legales-en-materia-penal-si-una-persona-se-encuentra-privada-de-libertad'
                    )

                    <div
                    x-data="{
                    edad: '',
                    requisitosMayor: [
                    'resolucion-judicial-que-conste-la-detencion-de-una-persona-prevencion-policial-auto-de-procesamiento-etc',
                    'fotocopia-del-boleto-de-ornato',
                    'fotocopia-simple-del-documento-personal-de-identificacion-de-quien-se-encuentra-privado-de-libertad'
                    ],
                    requisitosMenor: [
                    'resolucion-judicial-que-ordene-el-procesamiento-de-ninos-yo-adolescentes-en-conflicto-con-la-ley-penal',
                    'certificacion-de-nacimiento-extendida-por-renap',
                    ],

                    requisitosComunes: [
                        'fotocopia-simple-de-su-documento-personal-de-identificacion',
                        'fotocopia-de-recibo-agua-luz-o-telefono-del-lugar-de-su-residencia'
                    ],

                    }"
                    class="mt-6"
                    >


                        <p class=" text-center mt-2 mb-2 font-semibold text-[#03192B]">Seleccione su edad</p>

                        <div class="flex justify-center gap-6 mb-2">
                            <label class="flex items-center gap-1">
                                <input type="radio" x-model="edad" value="menor" wire:model.live="edad"> Menor de edad
                            </label>

                            <label class="flex items-center gap-1">
                                <input type="radio" x-model="edad" value="mayor" wire:model.live="edad"> Mayor de edad
                            </label>


                        </div>


                        <p class="text-center mt-1 text-sm text-red-600 font-semibold" x-text="edad === 'menor' ? 'Adolescentes en conflicto con la ley penal' : (edad === 'mayor' ? 'Privados de libertad por encontrarse en un proceso penal' : '')"></p>


                        <!-- DIBUJAR LOS REQUISITOS FILTRADOS -->
                        <div x-show="edad !== ''" x-transition>

                            <h2 class="text-center text-2xl font-bold mt-6 mb-2" style="color:#10069F">
                                REQUISITOS
                            </h2>

                            <p class="text-center text-sm p-2 rounded" style="background-color: #EFF6FF; color: #1E293B;">
                                Recuerde que puede subir únicamente documentos PDF o JPG
                            </p>

                            <div class="overflow-x-auto mt-4 border-t-4 border-b-4" style="border-color:#83BD3F;">

                                        
                            
                            
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b-4" style="border-color:#83BD3F;">
                                            <th class="px-4 py-3 font-bold text-[#03192B]">Requisitos</th>
                                            <th class="px-4 py-3 font-bold text-[#03192B] text-center">Acción</th>                                         
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @foreach($requisitos as $index => $requisito)
                                        
                                        <tr
                                        x-show="
                                        requisitosComunes.includes('{{ $requisito['slug']}}') || 
                                        (edad === 'mayor' && requisitosMayor.includes('{{ $requisito['slug'] }}'))
                                        || (edad === 'menor' && requisitosMenor.includes('{{ $requisito['slug'] }}'))
                                        "
                                        x-transition
                                        class="border-b-2"
                                        style="border-color:#83BD3F;"
                                        >
                                        <td class="px-4 py-3 text-[#03192B]">
                                            {{ $requisito['nombre'] }}

                                            @if ($requisito['slug'] === 'fotocopia-del-boleto-de-ornato')
                                                <strong>
                                                    (opcional)
                                                </strong>
                                            @endif
                                        </td>


                                        <td class="px-4 py-3 text-right">
                                            <div class="flex items-center justify-end gap-3">

                                                @if(!isset($requisitos[$index]['archivo']))
                                                <label class="cursor-pointer inline-flex items-center gap-2 bg-[#10069F] text-white px-4 py-2 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M4 12l7-8m0 0l7 8m-7-8v12" />
                                                </svg>
                                                <span>
                                                    Subir archivo
                                                </span>
                                                    

                                                    <input type="file"
                                                    wire:model="requisitos.{{ $index }}.archivo"
                                                    accept="application/pdf,image/jpeg"
                                                    class="hidden">
                                                </label>
                                                @else
                                                <p class="text-sm text-[#10069F] truncate w-40">
                                                    {{ $requisitos[$index]['archivo']->getClientOriginalName() }}
                                                </p>
                                                <button
                                                    type="button"
                                                    @click="abrirModalEliminarRequisito({{ $index }})"
                                                    class="text-red-600"
                                                >
                                                    ✕
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                        
                    @endif
           


                @if($tieneCargasFamiliares)
                <div>
                    <p class="mt-5 text-center text-[#03192B] font-semibold mb-2">
                                        ¿Desea agregar cargas familiares?

                    </p>


                    <div class="flex items-center justify-center gap-8 text-[#03192B]">
                        <label class="flex items-center gap-1">
                            <input type="radio" wire:model.live="agregarCargas" value="si">
                            Sí
                        </label>

                        <label class="flex items-center gap-1">
                            <input type="radio" wire:model.live="agregarCargas" value="no">
                            No
                        </label>
                    </div>
                </div>
                @endif


            @if($agregarCargas == 'si' && in_array($tramites->firstWhere('id', $tramite_id)?->slug, ['magisterio', 'tramites-legales-en-materia-civil']))

                <div wire:key="bloque-cargas">



                    

                     <div class="mt-6 mb-2 text-center text-sm text-[#03192B]">
                    Puede agregar hasta <strong> 4 cargas familiares </strong> .
                    ({{ count($cargas) }} / 4)
                </div>

                <p class="text-center text-sm p-2 rounded" style="background-color: #F0F0F0; color:#10069F">
                    Recuerde que puede subir únicamente documentos PDF o JPG
                </p>


                <ul class="text-center text-red-600 text-sm mt-1 bg-yellow-100 p-3 rounded list-disc list-inside space-y-1">
                    <li>Si es mayor de edad, cargar la fotocopia simple del Documento Personal de Identificación</li>
                    <li>Si es menor de edad, certificación de nacimiento.</li>
                    <li><strong>Colocar nombres y apellidos completos.</strong></li>
                </ul>



            

                   

              


                <div class="mt-6 mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                    <h3 class="text-lg font-bold text-[#03192B] mb-3 text-center">
                        Carga familiar
                    </h3>


                    <!-- barra de desplazamiento -->
                    <div class="overflow-x-auto">
                        

                        <div class="space-y-4">
                            @foreach ($cargas as $index => $carga )
                                    <div class="p-4 bg-white rounded shadow flex flex-col md:flex-row 
                                    md:items-center md:gap-4">

                                        <!-- Número de carga -->
                                        <div class="font-semibold text-[#03192B] mb-2 md:mb-0">
                                            Carga {{ $index + 1 }}
                                        </div>

                                        <!-- Nombres -->
                                        <div class="flex-1 mb-2 md:mb-0"> 
                                            <input type="text" placeholder="Nombres"
                                            wire:model.live="cargas.{{$index}}.nombres"
                                            class="border rounded px-3 py-2 w-full">
                                        </div>

                                        <!-- Apellidos -->
                                        <div class="flex-1 mb-2 md:mb-0">
                                            <input type="text" placeholder="Apellidos"
                                            wire:model.live="cargas.{{ $index }}.apellidos"
                                            class="border rounded px-3 py-2 w-full">
                                        
                                        </div>
                                        <!-- Subir archivo -->

                                        {{-- <div class="flex-shrink-0 flex items-center gap-2"> --}}
                                        <div class="flex-shrink-0 w-full md:w-auto">
                                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">

                                            @if (!isset($cargas[$index]['archivo']))

                                                <label class="
                                                    cursor-pointer
                                                    flex items-center justify-center gap-2
                                                    bg-[#83BD3F] text-white
                                                    px-4 py-2 rounded
                                                    hover:bg-green-700
                                                    w-full sm:w-auto
                                                ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M4 12l7-8m0 0l7 8m-7-8v12"/>
                                                    </svg>
                                                    Subir carga
                                                    <input type="file"
                                                        accept="application/pdf,image/jpeg"
                                                        class="hidden"
                                                        wire:model="cargas.{{ $index }}.archivo">
                                                </label>

                                            @else

                                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                                    <p class="text-[#10069F] text-sm truncate max-w-full sm:max-w-[120px]"
                                                        title="{{ $cargas[$index]['archivo']->getClientOriginalName() }}">
                                                        {{ $cargas[$index]['archivo']->getClientOriginalName() }}
                                                    </p>

                                                    <button
                                                        type="button"
                                                        @click="$dispatch('abrir-modal-eliminar-carga', {{ $index }})"
                                                        class="text-red-600 hover:text-red-800 flex-shrink-0"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M3 6h18M9 6v12m6-12v12M5 6l1 14h12l1-14" />
                                                        </svg>
                                                    </button>
                                                </div>

                                            @endif

                                        </div>
                                        </div>

                                        <td class="px-4 py-3 text-center"> @if($index > 0) <button type="button" wire:click="eliminarCarga({{ $index }})" class="text-red-600 font-bold text-lg hover:text-red-800" title="Eliminar carga" > ✕ </button> @endif </td>
                                        

                                    </div>
                                @endforeach
                                
                        </div>

                                
                                
                                
                    </div>
                </div>


                   <!-- agregar cargas boton -->
                      <!-- boton para agregar otra carga-->
                                 @if(count($cargas) < 4)
                                 <div class="mt-4 flex justify-center">
                                    <button
                                    type="button"
                                    wire:click="agregarCarga"
                                    class="flex items-center gap-2 bg-blue-600 text-white
                                    px-4 py-2 rounded hover:bg-blue-700 transition mb-5"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-white"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Agregar otra carga
                                </button>



                                 </div>
                                 @endif


                      </div>



                @endif


                <!-- Agregarlo en caso de cargas familiares -->


            @endif

                    {{-- <button type="button"
                @click="pasoAnterior()"
                class="mt-4 px-4 py-2 bg-gray-400 text-white rounded">
                Atrás
            </button> --}}


                    {{-- <button type="button"
                wire:click="verRequisitos"
                class="mt-3 px-4 py-2 bg-black text-white rounded hover:bg-gray-800">
                Ver Requisitos
            </button> --}}


            <div class="mt-4 flex justify-end">
            <button type="button"
                @click="$wire.validarPaso(2).then(valid => valid ? siguientePaso() : null)"
                class="mt-4 px-4 py-2 bg-black hover:bg-gray-800 text-white rounded"
                >
                Siguiente
            </button>
            </div>



    </div>

    <!-- Paso 3 -->
    <div x-show="paso === 3">

        <div>
            <x-label class="block text-sm font-medium mb-1 xl font-bold text-[#03192B]">Observaciones (opcional)</x-label>
            <x-textarea wire:model.defer="observaciones" class="border rounded px-3 py-2 w-full" rows="3"></x-textarea>
        </div>

        {{-- <button type="button"
                @click="pasoAnterior()"
                class="mt-4 px-4 py-2 bg-gray-400 text-white rounded">
                Atrás
        </button> --}}





        <div>
            <button type="submit" class="w-full bg-black text-white px-4 py-2 font-semibold rounded hover:bg-gray-800">
                Enviar
            </button>
        </div>

    </div>


    </form>

</div>


</div>
