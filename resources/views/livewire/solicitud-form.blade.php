{{-- intl-tel-input --}}

<div
x-data="{
paso: 1,
{{-- toast: @entangle('toast'), --}}
mostrarConfirmacion: false,
confirmarEnvio() {
   this.mostrarConfirmacion = true;
},
siguientePaso(){
this.paso++;
},
pasoAnterior(){
this.paso--;
}
}"

x-on:abrir-modal-confirmacion.window="mostrarConfirmacion = true"

class="max-w-2xl mx-auto my-20 bg-white border border-[#E4E4E4]
rounded-lg p-6 space-y-4 border border-[#EAEAEA] shadow-[0_0_10px_#EAEAEA]
p-8 rounded-xl"

>

<img src="/storage/imagenes/icono_muni.png" alt="Icono" class="w-20 mx-auto">


   <h1 class="text-3xl font-extrabold text-center text-[#10069F] mb-4">
    Constancia de residencia
    </h1>


    <p class="text-[#03192B]  text-center mb-6">
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
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


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
            <p class="text-[#03192B]"> ¿Estás seguro de que deseas enviar la solicitud?
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;Por favor revisa tus datos antes de continuar
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

            x-show="$wire.mostrarExito" 

            x-transition

            wire:key="modal-exito-{{ $ultimoNoSolicitud }}"

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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div x-data="{ valor: '' }"  x-on:form-reset.window="valor = ''">
                <x-label class="mb-1 font-bold text-[#03192B]">
                    Nombres 
                            <span class="text-red-600" x-show="valor === ''">*</span>

                </x-label>
                <x-input type="text" 
                placeholder="Ingrese sus nombres" 
                wire:model.defer="nombres" 
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
                wire:model.defer="apellidos" 
                x-model="valor"
                class="placeholder-[#797775] border rounded px-3 py-2 w-full" />
            </div>

            <div x-data="{ valor: '' }" x-on:form-reset.window="valor = ''">
                <x-label class="mb-1 font-bold text-[#03192B]">
                    Email
                    <span class="text-red-600" x-show="valor === ''">*</span>
                </x-label>
                <x-input type="email" 
                placeholder="Ingrese tu email" 
                wire:model.defer="email"
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
                    placeholder="Ingrese tu número"
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
                    CUI
                    <span class="text-red-600" x-show="valor === ''">*</span>
                </x-label>
                <x-input type="text" 
                placeholder="Ingrese su cui" 
                wire:model.defer="cui" 
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
                wire:model.defer="zona_id" 
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
            wire:model.defer="domicilio" 
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
    <div x-show="paso === 2" wire:key="paso-2-{{ $tramite_id }}">

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

                <!-- titulo centrado -->
                <h2 class="text-center text-2xl font-bold mt-6 mb-2" style="color:#10069F">
                    REQUISITOS
                </h2>

                <p class="text-center text-sm mb-4" style="color:#03192B;">
                    Recuerda que puedes subir únicamente <strong> PDF </strong> o <strong>JPG</strong>
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
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b-4" style="border-color:#83BD3F;">
                                <th class="px-4 py-3 font-bold text-[#03192B]">Requisitos</th>
                                <th class="px-4 py-3 font-bold text-[#03192B] text-center">Agregar PDF / JPG</th>
                                
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($requisitos as $index => $requisito)
                                <tr class="border-b-2" style="border-color:#83BD3F;">
                                    <td class="px-4 py-3 text-[#03192B]">
                                        {{ $requisito['nombre'] }}
                                    </td>

                                    <td class="px-4 py-3 text-right">
                                        {{-- <input 
                                            type="file" 
                                            wire:model="requisitos.{{ $index }}.archivo"
                                            accept="application/pdf,image/jpeg"
                                            class="block mx-auto"
                                        > --}}

                                        <label class="cursor-pointer inline-flex items-center gap-2 
                                        bg-[#10069F] text-white px-4 py-2 rounded hover:bg-[#0d057f]">

                                                {{-- <x-heroicons-outline.adjustments-horizontal /> --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M4 12l7-8m0 0l7 8m-7-8v12" />
                                                </svg>

                                                <span>Subir archivo</span>

                                                <input 
                                                type="file" 
                                                wire:model="requisitos.{{ $index }}.archivo"
                                                accept="application/pdf,image/jpeg"
                                                class="hidden"
                                                >
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
