<x-interno-layout :breadcrumb="[
   [
    'name' => 'Dashboard',
    'url' => route('interno.emision-constancia.index')
   ],
   [
    'name' => 'Emisión de constancias'
   ]
]">


@livewire('emision-constancias-table')


 <!-- modal para ver acciones -->

    <div
    x-data="{
    open: false,
    openPorAutorizar: false,
    solicitud: {},
    constanciaGenerada: false,
    constanciaFile: null,
    openEmitir: false,
}"

x-on:constancia-generada.window="
    constanciaGenerada = true;
    constanciaFile = $event.detail.path;
"

    x-on:solicitud-por-autorizar.window="
        openPorAutorizar = false;
        open = false;
    "

    x-on:constancia-emitida.window="
    solicitud = $event.detail.solicitud;
    constanciaGenerada = true;
    constanciaFile = solicitud.constancia_path ?? null;

    if(constanciaFile) {
        // Creamos un elemento 'a' invisible
        const link = document.createElement('a');
        link.href = '/storage/' + constanciaFile;
        
        // El atributo 'download' fuerza la descarga
        link.download = solicitud.no_solicitud + '-constancia.pdf';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
"

  @open-modal-detalle.window="
    open = true;
    solicitud = $event.detail.solicitud;
    constanciaGenerada = solicitud.constancia_generada === true; 
    {{-- puedo dejarlo en null para mientras --}}
     constanciaFile = solicitud.constancia_path ?? null;
"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>

   

<!-- MODAL DE EMITIR CONSTANCIA -->

<div
  x-show="openEmitir"
  x-cloak
  class="fixed inset-0 z-[200] flex items-center justify-center"
>

  <!-- Overlay -->
  <div
    class="fixed inset-0 bg-black bg-opacity-50"
    @click="openEmitir = false"
  ></div>

  <!-- Modal -->
  <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">

    <!-- Header -->
    <div class="flex items-center justify-between">

      <div class="flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-6 w-6 text-[#7A5C2E]"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 20h10a2 2 0 002-2V8l-6-6H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6"/>
        </svg>

        <h3 class="text-lg font-bold text-gray-800">
          Emitir constancia
        </h3>
      </div>

      <!-- Cerrar -->
      <button
        @click="openEmitir = false"
        type="button"
        class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-colors"
        aria-label="Cerrar modal"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <!-- Contenido -->
    <p class="font-bold text-gray-700 mt-3">
      ¿Desea generar la constancia de la solicitud
      <span class="text-gray-900" x-text="solicitud.no_solicitud"></span>?
    </p>

    <div class="mt-4 bg-[#EBD4A9]/30 p-3 rounded-lg text-sm text-gray-800">
      Al confirmar:
      <ul class="list-disc ml-5 mt-1">
        <li>Se generará la constancia</li>
        <li>El estado cambiará a <strong>Emitido</strong></li>
        <li>Se registrará en bitácora</li>
      </ul>
    </div>

    <!-- Acciones -->
    <div class="flex justify-end gap-3 mt-6">
      <button
        @click="openEmitir = false"
        class="px-4 py-2 text-sm font-bold bg-gray-200 rounded-lg"
      >
        Cancelar
      </button>

      <button
        @click="
          Livewire.dispatch('emitir-constancia');
          openEmitir = false;
        "
        class="px-4 py-2 text-sm font-bold text-gray-900 rounded-lg bg-[#EBD4A9]"
      >
        Sí, emitir
      </button>
    </div>

  </div>
</div>

      

<div x-show="open" 
     x-transition:enter="ease-out duration-300" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity z-50" 
     @click="open = true">
     
</div>

<div x-show="open" 
     class="fixed inset-0 z-50 overflow-y-auto">


     <div class="fixed inset-0 bg-gray-900/20 backdrop-blur-sm transition-opacity"
         @click="open = false">
     </div>


    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        
        <div x-show="open"
             x-cloak
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
            
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white leading-tight">
                            Detalle de Solicitud
                        </h3>
                        <p class="text-blue-100 text-sm font-medium">No. <span x-text="solicitud.no_solicitud"></span></p>
                    </div>
                </div>

                <button @click="open = false" type="button" class="text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <span class="text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></span>
                            <h4 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Información del Solicitante</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider">Nombre Completo</label>
                                <p class="text-gray-900 font-semibold" x-text="solicitud.nombres + ' ' + (solicitud.apellidos || '')"></p>
                            </div>

                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider">DPI / CUI</label>
                                <p class="text-gray-900 font-mono" x-text="solicitud.cui"></p>
                            </div>

                            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider">Teléfono</label>
                                <p class="text-gray-900" x-text="solicitud.telefono"></p>
                            </div>

                            <div class="sm:col-span-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider">Correo Electrónico</label>
                                <p class="text-gray-900 truncate" x-text="solicitud.email"></p>
                            </div>

                            <div class="sm:col-span-2 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider">Domicilio / Zona</label>
                                <p class="text-gray-900 text-sm">
                                    <span x-text="solicitud.domicilio"></span> - <span class="font-bold text-blue-600" x-text="'Zona ' + (solicitud.zona?.nombre || '')"></span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl border border-blue-100">
                            <span class="text-xs font-bold text-blue-700 uppercase">Tipo de Trámite</span>
                            <span class="px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-full shadow-sm uppercase" x-text="solicitud.requisitos_tramites?.[0]?.tramite?.nombre || 'General'"></span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <span class="text-green-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                            <h4 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Historial de Movimientos</h4>
                        </div>

                        <div class="max-h-[300px] overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                            <template x-if="solicitud.bitacoras && solicitud.bitacoras.length > 0">
                                <template x-for="item in solicitud.bitacoras" :key="item.id">
                                    <div class="relative pl-4 border-l-2 border-blue-200 py-1">
                                        <div class="absolute -left-[9px] top-2 w-4 h-4 rounded-full bg-blue-500 border-2 border-white"></div>
                                        <div class="bg-gray-50 p-3 rounded-lg border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-xs font-bold text-gray-900 uppercase" x-text="item.evento"></span>
                                                <span class="text-[10px] text-gray-400 font-medium" x-text="item.fecha_formateada"></span>
                                            </div>
                                            <p class="text-xs text-gray-600 italic" x-text="item.descripcion"></p>
                                            <p class="text-[10px] mt-2 font-bold text-blue-500 uppercase tracking-tighter" x-text="'Por: ' + (item.user?.name || 'Sistema')"></p>
                                        </div>
                                    </div>
                                </template>
                            </template>

                            <template x-if="!solicitud.bitacoras || solicitud.bitacoras.length === 0">
                                <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                    <p class="text-xs font-bold text-gray-400">Sin movimientos registrados</p>
                                </div>
                            </template>
                        </div>

                        <div class="bg-gray-900 rounded-2xl p-4 shadow-inner">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Personas Dependientes</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-if="solicitud.dependientes && solicitud.dependientes.length > 0">
                                    <template x-for="dep in solicitud.dependientes" :key="dep.id">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gray-800 text-green-400 text-xs border border-gray-700">
                                            <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                                            <span x-text="dep.nombres + ' ' + (dep.apellidos || '')"></span>
                                        </span>
                                    </template>
                                </template>
                                <template x-if="!solicitud.dependientes || solicitud.dependientes.length === 0">
                                    <span class="text-xs text-gray-500 italic">No registra dependientes</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                    <button type="button" 
                    x-show="solicitud.estado?.nombre === 'Por autorizar'"
                            class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-white px-8 py-3 text-sm font-bold text-red-600 border-2 border-red-100 hover:bg-red-50 hover:border-red-200 transition-all shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        No autorizar
                    </button>
{{-- 
                    <button
                        x-show="!constanciaGenerada"
                        @click="
                            if(confirm('¿Desea generar la constancia?')){
                                Livewire.dispatch('generar-constancia')
                            }
                        "
                        class="w-full sm:w-auto rounded-xl bg-emerald-600 px-8 py-3
                            text-sm font-black text-white shadow-lg hover:bg-emerald-700 transition-all">
                        Generar constancia
                    </button>
                     --}}

                   {{-- <button
                        x-show="!constanciaGenerada"
                        @click="
                            if(confirm('¿Desea generar la constancia?')){
                                Livewire.dispatch('generar-constancia')
                            }
                        "
                        class="w-full sm:w-auto rounded-xl bg-emerald-600 px-8 py-3
                            text-sm font-black text-white shadow-lg hover:bg-emerald-700 transition-all">
                        Generar constancia
                    </button> --}}

                   <button
                    type="button"
                    @click="openEmitir = true"
                    x-show="solicitud.estado?.nombre === 'Por autorizar'"
                    class="w-full md:w-auto inline-flex items-center justify-center
                        rounded-xl px-6 py-3.5 text-sm font-black
                        border transition-all transform active:scale-95 group
                        text-[#7A5C2E] bg-[#EBD4A9]/40 border-[#EBD4A9]
                        hover:bg-[#EBD4A9] hover:text-[#5A3F1C]">

                    <svg class="w-4 h-4 mr-2 transition-transform group-hover:scale-110"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7 20h10a2 2 0 002-2V8l-6-6H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 16l2 2 4-4" />
                    </svg>

                    EMITIR CONSTANCIA
                </button>



                                        
                        {{-- <button
                        x-show="constanciaGenerada"
                        @click="openPorAutorizar = true"
                        x-show="solicitud.estado?.nombre === 'Emitido'"
                        class="inline-flex items-center justify-center rounded-xl
                            bg-blue-600 px-10 py-3.5 text-sm font-black text-white
                            shadow-xl hover:bg-blue-700 transition-all">
                        Autorizar Solicitud
                        </button> --}}
                </div>


                <!-- aca mostrare los documentos de la persona -->

                <div x-show="constanciaGenerada && solicitud.estado?.nombre === 'Emitido'" x-transition class="mt-6 w-full">

                <div x-show="constanciaGenerada" x-transition class="mt-6 w-full">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                            <div class="flex items-center gap-2 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-emerald-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12h6m-6 4h6M7 20h10a2 2 0 002-2V8l-6-6H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>

                                <h4 class="text-lg font-bold text-gray-800">
                                    Constancia generada
                                </h4>
                            </div>



                            <template x-if="constanciaFile">
                                <a
                                    :href="`/storage/${constanciaFile}`"
                                    download
                                    class="inline-flex items-center gap-2 text-emerald-700 font-bold text-sm hover:underline"
                                >
 
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 16v-4m0 0V8m0 4h4m-4 0H8m8 4H8a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v7a2 2 0 01-2 2z"/>
                                    </svg>
                                    Ver / Descargar constancia
                                </a>
                            </template>

                            <template x-if="!constanciaFile">
                                <p class="text-xs text-emerald-600 italic">
                                    Constancia generada correctamente.
                                </p>
                            </template>

                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{--   
   <div 
  x-show="openPorAutorizar"
  x-on:solicitud-autorizada.window="openPorAutorizar = false"
  x-cloak
  class="fixed inset-0 z-[100] flex items-center justify-center p-4">

  <div 
    class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm"
    @click="openPorAutorizar = false">
  </div>

  <div 
    x-show="openPorAutorizar"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden">

    <div class="h-2 bg-[#3E88FF] w-full"></div>

    <div class="p-6">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-3">
          <div class="flex-shrink-0 w-10 h-10 bg-[#3E88FF]/10 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-[#3E88FF]"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

            <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16v10H4z" />
            <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6l8 6 8-6" />

            <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M14 14l3 2-3 2" />
            </svg>
            
                        </div>
          <div>
            <h3 class="text-xl font-bold text-gray-900">
              Mandar a autorizar
            </h3>
          </div>
        </div>

        <button
          @click="openPorAutorizar = false"
          class="text-gray-400 hover:text-gray-600 transition-colors p-1">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="mt-5">
        <p class="text-gray-700 text-base">
          ¿Está seguro que desea enviar para autorizar la solicitud no.
          <span class="font-bold text-gray-900" x-text="solicitud.no_solicitud"></span>?
        </p>

        <div class="mt-3 bg-blue-50 border-l-4 border-[#3E88FF] p-3">
          <div class="flex">
            <div class="flex-shrink-0">
     
              

            </div>
            <div class="ml-3">
              <p class="text-sm text-blue-700">
                Una vez enviado, el estado cambiará a
                <strong>"Por Autorizar"</strong> y el trámite continuará
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">
        <button
          @click="openPorAutorizar = false"
          class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all order-2 sm:order-1">
          No, cancelar
        </button>

        <button
          @click="Livewire.dispatch('constanciaAutorizar', { id: solicitud.id })"
          class="px-5 py-2.5 text-sm font-bold text-white 
                 bg-[#3E88FF] hover:bg-[#2F74E6]
                 rounded-xl shadow-lg shadow-[#3E88FF]/30
                 transition-all transform active:scale-95
                 order-1 sm:order-2">
          Sí, enviar
        </button>
      </div>
    </div>
  </div>
</div> --}}

  
<!-- nuevo modal -->

 

   </div>
   

</x-interno-layout>