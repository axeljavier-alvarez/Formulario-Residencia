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
    openRechazoEmision: false,
    errorRechazo: null,
    constanciaGenerada: false,
    constanciaFile: null,
    openEmitir: false,
    showDocs: false,
        descripcion: '',
    hasTipo(tipo) {
            return this.solicitud?.detalles?.some(d => d.tipo === tipo);
        }
    
}"


x-on:constancia-generada.window="
    constanciaGenerada = true;
    constanciaFile = $event.detail.path;
"
    x-on:error-rechazo.window="
    errorRechazo = $event.detail.mensaje
    "
    x-on:rechazo-exitoso.window="
        openRechazoEmision = false;
        open = false;
        descripcion = '';
        errorRechazo = null;
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

   


<div 
    x-show="openRechazoEmision" 
    x-cloak 
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
>
    <div 
        x-show="openRechazoEmision"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" 
        @click="openRechazoEmision = false"
    ></div>

    <div 
        x-show="openRechazoEmision"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden"
    >
        <div class="h-2 bg-red-500 w-full"></div>

        <div class="p-6">

            <div class="flex items-start justify-between">

                <div class="flex items-center gap-3">
                    <div class="relative flex items-center justify-center w-12 h-12">
                        <div class="absolute inset-0 bg-red-500 rounded-full opacity-20 animate-pulse"></div>
                        <div class="relative w-10 h-10 bg-red-500 rounded-full flex items-center justify-center shadow-lg shadow-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Rechazar Solicitud</h3>
                        <p class="text-sm text-gray-500 leading-tight">Esta acción no se puede deshacer</p>
                    </div>
                </div>


                  <button @click="openRechazoEmision = false"
                        class="text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

            </div>



              <div class="mt-5">
                <p class="text-gray-700 text-base">
                    ¿Está seguro que desea rechazar la solicitud <span class="font-bold text-[#3B82F6]" x-text="solicitud.no_solicitud"></span>?
                </p>

                <div x-show="errorRechazo" x-cloak
                     class="mt-3 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span x-text="errorRechazo"></span>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">
                        Motivo del rechazo <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        x-model="descripcion"
                        rows="4"
                        class="w-full border-gray-200 rounded-xl p-3 text-sm focus:border-red-500 focus:ring focus:ring-red-100 transition-all resize-none bg-gray-50"
                        placeholder="Describa por qué se rechaza la solicitud...">
                    </textarea>
                </div>
            </div>


            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">
                <button @click="openRechazoEmision = false"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all order-2 sm:order-1">
                    No, cancelar
                </button>

                <button @click="openRechazoEmision = null; Livewire.dispatch('peticionRechazar', { id: solicitud.id, descripcion: descripcion });"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-200 transition-all transform active:scale-95 order-1 sm:order-2">
                    Confirmar rechazo
                </button>
            </div>
        </div>
    </div>
</div>


<!-- MODAL DE EMITIR CONSTANCIA -->
<!-- MODAL EMITIR CONSTANCIA -->
<!-- MODAL EMITIR CONSTANCIA -->
<div x-show="openEmitir" x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4">

    <!-- Fondo -->
    <div x-show="openEmitir"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="openEmitir = false">
    </div>

    <!-- Modal -->
    <div x-show="openEmitir"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden">

        <!-- Barra superior -->
        <div class="h-2 bg-[#EBD4A9] w-full"></div>

        <div class="p-6">

            <!-- Header -->
            <div class="flex items-start justify-between">

                <div class="flex items-center gap-3">

                    <div class="relative flex items-center justify-center w-12 h-12">
                        <div class="absolute inset-0 bg-[#EBD4A9] rounded-full opacity-20 animate-pulse"></div>

                        <div class="relative w-10 h-10 bg-[#EBD4A9] rounded-full flex items-center justify-center shadow-lg shadow-yellow-200">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6 text-gray-900"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2.5"
                                      d="M7 20h10a2 2 0 002-2V8l-6-6H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M9 12h6m-6 4h6"/>
                            </svg>
                        </div>
                    </div>




                    
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            Emitir Constancia
                        </h3>
                        <p class="text-sm text-gray-500">
                            Esta acción generará el documento oficial
                        </p>
                    </div>

                </div>

                <!-- Cerrar -->
                <button
                    @click="openEmitir = false"
                    class="text-gray-400 hover:text-[#EBD4A9] hover:bg-yellow-50 rounded-full transition-all p-1">

                    <svg class="w-6 h-6"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12">
                        </path>

                    </svg>
                </button>

            </div>

            <!-- Contenido -->
            <div class="mt-5">

                <p class="text-gray-700 text-base">
                    ¿Desea generar la constancia de la solicitud
                    <span class="font-bold text-[#7A5C2E]"
                          x-text="solicitud.no_solicitud">
                    </span>?
                </p>

                <div class="mt-4 bg-[#EBD4A9]/30 border border-[#EBD4A9] p-3 rounded-xl text-sm text-gray-800">

                    <p class="font-semibold mb-1">Al confirmar:</p>

                    <ul class="list-disc ml-5 space-y-1">
                        <li>Se generará la constancia</li>
                        <li>El estado cambiará a <strong>Emitido</strong></li>
                        <li>Se registrará en bitácora</li>
                    </ul>

                </div>

            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">

                <button
                    @click="openEmitir = false"
                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all">

                    Cancelar
                </button>

                <button
                    @click="
                        Livewire.dispatch('emitir-constancia', { id: solicitud.id });
                        openEmitir = false;
                    "
                    class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-gray-900 bg-[#EBD4A9] hover:bg-[#e0c791] rounded-xl shadow-lg transition-all transform active:scale-95">

                    Sí, emitir constancia
                </button>

            </div>

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
                                <span class="text-[11px] text-orange-400/80 italic flex items-center gap-1.5">
                                                        <i class="fas fa-info-circle"></i> No se ingresaron dependientes
                                                    </span>                                
                                                </template>
                            </div>
                        </div>


                        
                    </div>
                </div>


                <div class="mt-3 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">
                                    Observaciones
                                </label>

                                <p class="text-gray-900 font-mono font-medium"
                                x-text="solicitud.observaciones ? solicitud.observaciones : 'El solicitante no ingresó observaciones'">
                                </p>
                    </div>
                    

                <div class="mt-10 flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                  <button type="button" 
                        @click="openRechazoEmision = true"
                        x-show="solicitud.estado?.nombre === 'Por autorizar'"
                        class="w-full md:w-auto inline-flex items-center justify-center rounded-xl bg-red-50 px-6 py-3.5 text-sm font-black text-red-600 border border-red-100 hover:bg-red-600 hover:text-white transition-all transform active:scale-95 group">
                        
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        
                        RECHAZAR
                    </button>


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

                                        
              
                </div>


                <!-- aca mostrare los documentos de la persona -->
                <button 
                x-show="solicitud.estado?.nombre === 'Emitido'"
                x-transition
                    @click="showDocs = !showDocs" 
                    class="flex items-center justify-between w-full p-4 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all duration-200"
                >
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-lg font-bold text-gray-800">Documentación de la solicitud</span>
                    </div>
                    
                    <svg 
                        class="w-5 h-5 text-gray-500 transition-transform duration-300" 
                        :class="showDocs ? 'rotate-180' : ''" 
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                

    <div 
        x-show="showDocs" 
        x-collapse 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        class="mt-4 space-y-6"
    >
        
        <div x-show="constanciaGenerada && solicitud.estado?.nombre === 'Emitido'" class="w-full">
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M7 20h10a2 2 0 002-2V8l-6-6H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <h4 class="text-lg font-bold text-gray-800">Constancia generada</h4>
                </div>

                <template x-if="constanciaFile">
                    <a :href="`/storage/${constanciaFile}`" download class="inline-flex items-center gap-2 text-emerald-700 font-bold text-sm hover:underline">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v-4m0 0V8m0 4h4m-4 0H8m8 4H8a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v7a2 2 0 01-2 2z"/>
                        </svg>
                        Ver / Descargar constancia
                    </a>
                </template>
                <template x-if="!constanciaFile">
                    <p class="text-xs text-emerald-600 italic">Constancia generada correctamente.</p>
                </template>
            </div>
        </div>

        <template x-if="solicitud">
            <div class="space-y-4">
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                    <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Documentos 
                    </h4>
                    <div class="space-y-3">
                        <template x-for="detalle in solicitud.detalles" :key="detalle.id">
                            <div x-show="detalle.tipo === 'normal' || detalle.tipo === 'carga'" 
                                 class="flex justify-between items-center text-sm border bg-white p-3 rounded-lg shadow-sm hover:border-emerald-400 transition-colors">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-700" x-text="detalle.requisito_tramite?.requisito?.nombre || 'Documento'"></span>
                                    <span class="text-[10px] uppercase text-gray-400 font-semibold" x-text="detalle.tipo"></span>
                                </div>
                                <a :href="'/storage/' + detalle.path" target="_blank" class="inline-flex items-center px-3 py-1 bg-emerald-600 text-white rounded-md text-xs font-bold hover:bg-emerald-700">
                                    Ver Archivo
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                   <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-4">

                    <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4 flex items-center gap-2">

                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>

                        </svg>

                        Archivos de dependientes

                    </h4>



                    <div class="space-y-3">

                        <template x-if="solicitud.detalles.some(d => d.tipo === 'carga')">

                            <div>

                                <template x-for="detalle in solicitud.detalles" :key="detalle.id">

                                    <div x-show="detalle.tipo === 'carga'" 

                                        class="flex justify-between items-center text-sm border bg-white p-3 rounded-lg shadow-sm hover:border-blue-400 transition-colors mb-3 last:mb-0">

                                        

                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-700"
                                            x-text="detalle.dependiente 
                                                    ? detalle.dependiente.nombres + ' ' + (detalle.dependiente.apellidos || '') 
                                                    : 'Dependiente'">
                                            </span>

                                            

                                            <span class="text-[10px] uppercase text-blue-500 font-semibold">Carga Familiar</span>

                                        </div>

                                        

                                        <a :href="'/storage/' + detalle.path" target="_blank" 

                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded-md text-xs font-bold hover:bg-blue-700 transition-colors">

                                            Ver Archivo

                                        </a>

                                    </div>

                                </template>

                            </div>

                        </template>



                        <template x-if="!solicitud.detalles.some(d => d.tipo === 'carga')">

                            <div class="flex flex-col items-center justify-center py-8 text-blue-400 border-2 border-dashed border-blue-100 rounded-lg">

                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>

                                </svg>

                                <p class="text-sm font-medium italic">No se registraron dependientes para esta solicitud</p>

                            </div>

                        </template>

                    </div>

                </div>

                



                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-4">

                                    <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4 flex items-center gap-2">

                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>

                                        Evidencias de Visita de Campo

                                    </h4>



                                    <template x-if="hasTipo('foto_visita')">

                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">

                                            <template x-for="detalle in solicitud.detalles" :key="detalle.id">

                                                <div x-show="detalle.tipo === 'foto_visita'" class="group relative">

                                                    <img :src="'/storage/' + detalle.path" 

                                                        class="w-full h-32 object-cover rounded-lg border-2 border-white shadow-md">

                                                    <a :href="'/storage/' + detalle.path" target="_blank" 

                                                    class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg text-white text-xs font-bold">

                                                        Ampliar Foto

                                                    </a>

                                                </div>

                                            </template>

                                        </div>

                                    </template>



                                    <template x-if="!hasTipo('foto_visita')">

                                        <div class="flex flex-col items-center justify-center py-6 text-blue-400">

                                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>

                                            <p class="text-sm font-medium">No se realizó visita de campo / No hay fotos</p>

                                        </div>

                                    </template>

                                </div>

                            </div>

                        </template>

                    </div>


                    
            </div>
        </template>
    </div>

   

</x-interno-layout>