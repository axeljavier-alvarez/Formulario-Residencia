<x-interno-layout :breadcrumb="[
    [
      'name' => 'Dashboard',
      'url' => route('interno.dashboard.index')
    ],
    [
    'name' => 'Analisis de documentos'
    ]

]">


    @livewire('analisis-documentos-table')

<!-- CREACION DEL modal -->
<div


    x-data="{
    
            openPreview: false,
            imgSource: '',

    open:false,
    openAbrirExpediente: false,
    solicitud: {},

    openRechazo: false,
    openPorAutorizar: false,
    openVisitaCampo: false,
        openDocs: false,

    openDocumento: false,
    documentoActual: null,

    descripcion: '',
    errorRechazo: null,
    errorPrevio: null,
    
     {{-- PREVIO --}}
     openPrevio: false,
        documentosSeleccionados: [],
     {{-- codigo de cargas familiares --}}
    openCargas: false,
    verDocumento(path, nombre = 'Documento'){
    this.documentoActual = {
        path: path,
        nombre: nombre,
    };
    this.openDocumento = true;
    }


    }"

        @preview-foto.window="openPreview = true; imgSource = $event.detail.url"
        @abrir-modal-expediente.window="
        openAbrirExpediente = true;
        solicitud = $event.detail.solicitud
        "
         @close-confirm.window="
         openAbrirExpediente = false
         "

    x-on:error-rechazo.window="
    errorRechazo = $event.detail.mensaje
    "
    x-on:rechazo-exitoso.window="
        openRechazo = false;
        open = false;
        descripcion = '';
        errorRechazo = null;
    "

    x-on:error-previo.window="
    errorPrevio = $event.detail.mensaje
    "
    x-on:previo-exitoso.window="
        openPrevio = false;
        open = false;
        descripcion = '';
        documentosSeleccionados = [];
        errorPrevio = null;
    "

    x-on:solicitud-por-autorizar.window="
        openPorAutorizar = false;
        open = false;
    "

    x-on:solicitud-visita-campo.window="
        openVisitaCampo = false;
        open = false;
    "





    @open-modal-solicitud.window="
    open = true;
    solicitud = $event.detail.solicitud
    "

    


    {{-- x-show="open" --}}
    x-cloak
    {{-- class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true" --}}


    {{-- x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-cabellad="modal-title"
    role="dialog"
    aria-modal="true" --}}
>

<!-- MODAL PREVIO -->
<div x-show="openPrevio" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openPrevio = false"></div>

    <div x-show="openPrevio"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden">

        <div class="h-2 bg-[#F46241] w-full"></div>

        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative flex items-center justify-center w-12 h-12">
                        <div class="absolute inset-0 bg-[#F46241] rounded-full opacity-20 animate-pulse"></div>
                        <div class="relative w-10 h-10 bg-[#F46241] rounded-full flex items-center justify-center shadow-lg shadow-[#F46241]/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Enviar a Previo</h3>
                        <p class="text-sm text-gray-500 leading-tight">Preparar solicitud para revisión</p>
                    </div>
                </div>

                <button @click="openPrevio = false"
                        class="text-gray-400 hover:text-[#F46241] hover:bg-[#F46241]/10 rounded-full transition-all p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-5">
                <p class="text-gray-700 text-base">
                    ¿Desea enviar la solicitud <span class="font-bold text-[#F46241]" x-text="solicitud.no_solicitud"></span> a previo?
                </p>

                <div x-show="errorPrevio" x-cloak
                     class="mt-3 p-3 bg-orange-50 border border-[#F46241]/20 text-[#F46241] text-sm rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span x-text="errorPrevio"></span>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-1 ml-1">
                        Descripción de envío <span class="text-[#F46241]">*</span>
                    </label>

                    <div class="mt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2 ml-1">
                            Documentos a corregir:
                        </label>
                        <div class="max-h-40 overflow-y-auto space-y-2 p-2 bg-gray-50 rounded-xl border border-gray-100">
                            <template x-for="(doc, index) in (solicitud.documentos || []).filter(d => d.tipo === 'normal')" :key="index">
                                <label class="flex items-center gap-3 p-2 bg-white rounded-lg border border-gray-100 cursor-pointer hover:bg-orange-50 transition-colors">
                                    <input type="checkbox" 
                                        :value="doc.titulo" 
                                        x-model="documentosSeleccionados"
                                        class="rounded border-gray-300 text-[#F46241] focus:ring-[#F46241]">
                                    <span class="text-xs font-medium text-gray-700" x-text="doc.titulo"></span>
                                </label>
                            </template>
                        </div>
                    </div>
                    <textarea
                        x-model="descripcion"
                        rows="4"
                        class="w-full border-gray-200 rounded-xl p-3 text-sm focus:border-[#F46241] focus:ring focus:ring-[#F46241]/20 transition-all resize-none bg-gray-50"
                        placeholder="Escriba la descripción del previo...">
                    </textarea>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">
                <button @click="openPrevio = false"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all order-2 sm:order-1">
                    Cancelar
                </button>

                <button @click="errorPrevio = null; Livewire.dispatch('peticionPrevio', { 
                    id: solicitud.id, 
                    descripcion: descripcion,
                    documentos: documentosSeleccionados
                 });"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-[#F46241] hover:bg-[#d95336] rounded-xl shadow-lg shadow-[#F46241]/20 transition-all transform active:scale-95 order-1 sm:order-2">
                    Confirmar envío a previo
                </button>
            </div>
        </div>
    </div>
</div>





<!-- MODAL PARA ABRIR DOCUMENTO -->
<div x-show="openDocumento" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center">


    <div class="fixed inset-0 bg-black bg-opacity-50" @click="openDocumento = false">

    </div>

    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl p-4 relative">
      <!-- ENCABEZADO DEL MODAL -->
      <div class="flex items-center justify-between border-b pb-2 mb-3">
        <h3 class="font-bold text-lg text-gray-800" x-text="documentoActual?.nombre">
        </h3>

        <div class="flex items-center gap-2">

          <template x-if="documentoActual && documentoActual.path">


             <a :href="`/storage/${documentoActual.path}`" target="_blank"
              class="p-2 text-blue-500 hover:bg-blue-50 rounded-full transition-colors"
              title="Abrir en pestaña nueva">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>

          </template>

          <button @click="openDocumento = false" class="p-2 text-red-500 hover:bg-red-50 rounded-full">✕</button>
    </div>

      </div>

      <!-- donde se vera el documento -->
      <div class="h-[70vh] border rounded-lg overflow-hidden">

        <!-- ver el pdf -->
            <template x-if="documentoActual?.path && documentoActual.path.endsWith('.pdf')">
                <iframe
                    :src="`/storage/${documentoActual.path}`"
                    class="w-full h-full"
                ></iframe>
            </template>


        <!--donde se vera la imagen -->
        <template x-if="documentoActual?.path && !documentoActual.path.endsWith('.pdf')">
        <img
            :src="`/storage/${documentoActual.path}`"
            class="w-full h-full object-contain"
            />
        </template>
      </div>



    </div>



</div>

<!-- MODAL DE DETALLE -->

     <div x-show="open"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 bg-gray-900/30 backdrop-blur-sm
 transition-opacity z-50"
    @click="open = false">
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

            <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-extrabold text-white leading-tight tracking-tight">Detalle de Solicitud</h3>
                        <p class="text-blue-100 text-xs font-bold opacity-90 uppercase tracking-widest">No. <span x-text="solicitud.no_solicitud"></span></p>
                    </div>
                </div>
                <button @click="open = false" type="button" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 lg:p-8">


                <!-- alerta -->
                <div 
                x-data="{ visible: false }" 
                @mostrar-alerta-analisis.window="visible = true; setTimeout(() => visible = false, 5000)"
                x-show="visible"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-end="opacity-0 scale-90"
                class="mb-8 relative overflow-hidden w-full"
            >
                <div class="flex items-center p-4 rounded-2xl border border-[#BEE7F0] shadow-sm relative z-10" style="background-color: #DAF4F9;">
                    <div class="flex-shrink-0 w-10 h-10 bg-white/60 rounded-xl flex items-center justify-center shadow-sm text-[#2D8BA3]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <div class="ml-4">
                        <h4 class="text-[10px] font-black text-[#2D8BA3] uppercase tracking-[0.1em] leading-none mb-1">Actualización de flujo</h4>
                        <p class="text-sm font-bold text-[#1A5E6E]">
                            El expediente ahora está <span class="px-2 py-0.5 bg-[#2D8BA3] text-white rounded-md text-[11px] font-black">EN ANÁLISIS</span>
                        </p>
                    </div>

                    <button @click="visible = false" class="ml-auto text-[#2D8BA3]/50 hover:text-[#2D8BA3]">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                    </button>
                </div>


                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/40 rounded-full blur-2xl"></div>
            </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <div class="space-y-6">


                                    
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <span class="text-blue-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg></span>
                            <h4 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Información del Solicitante</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="sm:col-span-2 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Nombre Completo</label>
                                <p class="text-gray-900 font-bold" x-text="solicitud.nombres + ' ' + (solicitud.apellidos || '')"></p>
                            </div>
                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">DPI / CUI</label>
                                <p class="text-gray-900 font-mono font-medium" x-text="solicitud.cui"></p>
                            </div>
                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Teléfono</label>
                                <p class="text-gray-900 font-medium" x-text="solicitud.telefono"></p>
                            </div>
                            <div class="sm:col-span-2 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                <label class="block text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Domicilio / Zona</label>
                                <p class="text-gray-900 text-sm">
                                    <span x-text="solicitud.domicilio"></span> - <span class="font-bold text-blue-600" x-text="(solicitud.zona?.nombre || '')"></span>
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                            <span class="text-xs font-bold text-blue-700 uppercase tracking-tight">Tipo de Trámite</span>
                            <span class="px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-lg shadow-sm uppercase" x-text="solicitud.requisitos_tramites?.[0]?.tramite?.nombre || 'General'"></span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <span class="text-emerald-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                            <h4 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Historial de Movimientos</h4>
                        </div>

                        <div class="max-h-[220px] overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                            <template x-if="solicitud.bitacoras && solicitud.bitacoras.length > 0">
                                <template x-for="item in solicitud.bitacoras" :key="item.id">
                                    <div class="relative pl-4 border-l-2 border-blue-100 py-1">
                                        <div class="absolute -left-[9px] top-2 w-4 h-4 rounded-full bg-blue-500 border-2 border-white shadow-sm"></div>
                                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 shadow-sm">
                                            <div class="flex justify-between items-start mb-1">
                                                <span class="text-[11px] font-bold text-gray-900 uppercase" x-text="item.evento"></span>
                                                <span class="text-[10px] text-gray-400 font-medium" x-text="item.fecha_formateada"></span>
                                            </div>
                                            <p class="text-xs text-gray-600 italic leading-relaxed" x-text="item.descripcion"></p>
                                            <p class="text-[10px] mt-2 font-bold text-blue-500 uppercase tracking-tighter" x-text="'Por: ' + (item.user?.name || 'Solicitante')"></p>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <template x-if="!solicitud.bitacoras || solicitud.bitacoras.length === 0">
                                <div class="text-center py-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Sin movimientos registrados</p>
                                </div>
                            </template>
                        </div>

                        <div class="bg-gray-900 rounded-2xl p-4 shadow-2xl border border-gray-800">
                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full shadow-[0_0_8px_rgba(34,197,94,0.5)]"></span>
                                Personas Dependientes
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-if="solicitud.documentos && solicitud.documentos.find(d => d.tipo === 'carga')">
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="dep in solicitud.documentos.find(d => d.tipo === 'carga').dependientes" :key="dep.id">
                                            <button @click="documentoActual = dep; openDocumento = true;"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-800 text-gray-300 hover:text-green-400 text-xs border border-gray-700 hover:border-green-500/40 transition-all cursor-pointer">
                                                <span x-text="dep.nombre"></span>
                                            </button>
                                        </template>
                                        <template x-if="solicitud.documentos.find(d => d.tipo === 'carga').dependientes.length === 0">
                                            <span class="text-[11px] text-orange-400/80 italic flex items-center gap-1.5">
                                                <i class="fas fa-info-circle"></i> No se ingresaron dependientes
                                            </span>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- VER LOS DOCUMENTOS -->
               <div class="mt-10">
                    <div class="flex items-center gap-2 pb-2 border-b border-gray-100 mb-5">
                        <span class="text-amber-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg></span>
                        <h4 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Documentos del Solicitante</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <template x-if="solicitud.documentos && solicitud.documentos.length > 0">
                            <template x-for="(doc, index) in solicitud.documentos" :key="index">
                                <template x-if="doc.tipo === 'normal'">
                                    <button @click="documentoActual = doc; openDocumento = true;"
                                        class="group flex items-center justify-between p-3.5 bg-white border border-gray-200 rounded-xl hover:border-blue-400 hover:shadow-lg hover:shadow-blue-50 transition-all duration-200">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </div>
                                            <span class="text-xs font-bold text-gray-700 truncate max-w-[150px]" x-text="doc.titulo"></span>
                                        </div>
                                        <i class="fas fa-external-link-alt text-[10px] text-gray-300 group-hover:text-blue-500"></i>
                                    </button>
                                </template>
                            </template>
                        </template>
                    </div>
                </div>


                <!-- VISITA DE CAMPO RESULTADO -->
<div
                 x-show="solicitud.estado?.nombre === 'Visita realizada'"
                 x-transition
                 class="mt-10 border border-gray-200 rounded-xl shadow-sm bg-white">




                 <button 
                    @click="openDocs = !openDocs" 
                    class="w-full flex justify-between items-center px-6 py-4 bg-gray-50 rounded-t-xl hover:bg-gray-100 transition-colors">
                    
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-bold text-gray-800 uppercase text-xs tracking-widest">
                            Resultados de la visita de campo
                        </span>
                    </div>

                    <!-- FLECHA QUE GIRA -->
                    <svg :class="{'rotate-180': openDocs}" class="w-5 h-5 text-gray-400 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>


                                    <div x-show="openDocs" x-transition class="px-6 py-4 space-y-6">

                                         <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">

        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200">

            <div class="p-2 bg-emerald-600 text-white rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
            </div>

            <div>
                <p class="text-[10px] uppercase font-bold text-emerald-700">Estado</p>
                <p class="font-black text-emerald-900">Visita realizada</p>
            </div>
        </div>

        <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200">
           
            <div class="p-2 bg-blue-600 text-white rounded-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 11a3 3 0 100-6 3 3 0 000 6z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11c0 5-7 10-7 10S5 16 5 11a7 7 0 1114 0z" />
                                    </svg>
            </div>

            <div>
                <p class="text-[10px] uppercase font-bold text-blue-700">Domicilio verificado</p>
                <p class="text-xs font-semibold text-blue-900" x-text="solicitud.domicilio"></p>
            </div>
        </div>

        <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 border">
            <div class="p-2 bg-gray-700 text-white rounded-lg">🕒</div>
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-600">Fecha</p>
                <p class="text-xs font-semibold text-gray-900">
                    <template
                        x-for="bit in solicitud.bitacoras?.filter(b => b.evento.includes('Visita realizada')).slice(0,1)">
                        <span x-text="bit.fecha_formateada"></span>
                    </template>
                </p>
            </div>
        </div>

    </div>

    <div class="mb-8">
        <div class="flex items-center mb-3">
            
             <span class="p-2 bg-amber-50 rounded-lg mr-2 text-amber-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2h8l4 4zM9 13h6M9 17h4" />
                                        </svg>
             </span>

            <h4 class="font-bold text-gray-800 uppercase text-sm tracking-wider">
                Observaciones de la visita
            </h4>
        </div>

        <template
            x-if="solicitud.bitacoras && solicitud.bitacoras.filter(b => b.evento.includes('Visita realizada')).length > 0">

            <div class="space-y-4">
                <template
                    x-for="bitacora in solicitud.bitacoras.filter(b => b.evento.includes('Visita realizada'))"
                    :key="bitacora.id">

                    <div class="relative pl-6 border-l-2 border-amber-300">
                        <span class="absolute -left-2 top-2 w-4 h-4 bg-amber-500 rounded-full"></span>

                        <div class="bg-amber-50 p-4 rounded-xl border border-amber-200">
                            <div class="text-sm text-gray-800 leading-relaxed" x-html="bitacora.descripcion"></div>
                            <span class="block mt-2 text-xs text-gray-500 font-mono"
                                  x-text="bitacora.fecha_formateada"></span>
                        </div>
                    </div>

                </template>
            </div>

        </template>

        <template
            x-if="!solicitud.bitacoras || solicitud.bitacoras.filter(b => b.evento.includes('Visita realizada')).length === 0">
            <div class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded-lg border border-dashed">
                No se registraron observaciones para esta visita
            </div>
        </template>
    </div>

    <div>
        <div class="flex items-center mb-3">
            <span class="p-2 bg-teal-50 rounded-lg mr-2 text-teal-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7h4l2-3h6l2 3h4v11a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                <circle cx="12" cy="13" r="3" />
                </svg>
            </span>

            <h4 class="font-bold text-gray-800 uppercase text-sm tracking-wider">
                Evidencia fotográfica
            </h4>
        </div>

        <template x-if="solicitud.fotos && solicitud.fotos.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <template x-for="foto in solicitud.fotos" :key="foto.id">
                    <div
                        class="group relative aspect-video rounded-xl overflow-hidden border shadow-sm cursor-pointer"
                        @click="$dispatch('preview-foto', { url: '/storage/' + foto.path })">

                        <img
                            :src="'/storage/' + foto.path"
                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                            loading="lazy"
                        >

                        <div
                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100
                                   flex items-center justify-center transition">
                            <span class="text-white text-xs font-bold uppercase tracking-widest">
                                Ver evidencia
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="!solicitud.fotos || solicitud.fotos.length === 0">
            <p class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded-lg border border-dashed">
                No se adjuntaron fotografías en esta visita
            </p>
        </template>
    </div>

                                    </div>

   

</div>


                <!-- BOTONES FINALES -->
                <div class="mt-12 pt-6 border-t border-gray-100">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                        <button type="button" @click="openRechazo = true"
                            x-show="!['Visita asignada'].includes(solicitud.estado?.nombre)"
                            class="w-full md:w-auto inline-flex items-center justify-center rounded-xl bg-red-50 px-6 py-3.5 text-sm font-black text-red-600 border border-red-100 hover:bg-red-600 hover:text-white transition-all transform active:scale-95 group">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            RECHAZAR SOLICITUD
                        </button>

                        <div class="flex flex-col sm:flex-row w-full md:w-auto gap-3">
                            <button 
                            type="button" 
                                @click="openVisitaCampo = true"
                                x-show="!['Visita realizada', 'Visita asignada'].includes(solicitud.estado?.nombre)"

                                class="w-full sm:w-auto inline-flex gap-2 items-center justify-center rounded-xl bg-amber-50 px-6 py-3.5 text-sm font-black text-amber-700 border border-amber-200 hover:bg-amber-500 hover:text-white transition-all transform active:scale-95">


                                <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        viewBox="0 0 512 512"
                                        fill="currentColor">
                                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7l91.3 91.3c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.1-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0s208 93.1 208 208zm-208 128c70.7 0 128-57.3 128-128S278.7 80 208 80 80 137.3 80 208s57.3 128 128 128zm0-208c-44.2 0-80 35.8-80 80 0 17.7-14.3 32-32 32s-32-14.3-32-32c0-79.5 64.5-144 144-144 17.7 0 32 14.3 32 32s-14.3 32-32 32z"/>
                                    </svg>

                                INSPECCIÓN DE CAMPO
                            </button>

                            <button type="button"
                                @click="openPrevio = true"
                                x-show="solicitud.estado?.nombre === 'Analisis'"
                                class="w-full md:w-auto inline-flex items-center justify-center rounded-xl
                                    bg-orange-50 px-6 py-3.5 text-sm font-black text-orange-600
                                    border border-orange-200 hover:bg-orange-500 hover:text-white
                                    transition-all transform active:scale-95">

                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.7-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                                </svg>

                                ENVIAR A PREVIO
                            </button>


                            <button
                                type="button"
                                @click="openPorAutorizar = true"
                                x-show="!['Visita asignada'].includes(solicitud.estado?.nombre)"
                                class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl
                                    bg-[#4564EE] hover:bg-[#3651D1]
                                    px-10 py-3 text-sm font-bold text-white
                                    shadow-lg shadow-blue-200
                                    transition-all transform active:scale-95">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6M7 8h10M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H9l-4 4v10a2 2 0 002 2z"/>
                                </svg>

                                ENVIAR A AUTORIZAR
                            </button>

                        </div>
                    </div>
                </div>






            </div>
        </div>
    </div>






       <!-- MODAL DE RECHAZO -->
<div x-show="openRechazo" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openRechazo = false"></div>

    <div x-show="openRechazo"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden">

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

                <button @click="openRechazo = false"
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
                <button @click="openRechazo = false"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all order-2 sm:order-1">
                    No, cancelar
                </button>

                <button @click="errorRechazo = null; Livewire.dispatch('peticionRechazar', { id: solicitud.id, descripcion: descripcion });"
                        class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-200 transition-all transform active:scale-95 order-1 sm:order-2">
                    Confirmar rechazo
                </button>
            </div>
        </div>
    </div>
</div>



  <!-- MODAL DE VISITA DE CAMPO -->
<div x-show="openVisitaCampo"
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4">

    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="openVisitaCampo = false">
    </div>

    <div x-show="openVisitaCampo"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden">

        <div class="h-2 bg-[#FFAA0D] w-full"></div>

        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                   <div class="relative flex items-center justify-center w-12 h-12">
                        <div class="absolute inset-0 bg-[#FFAA0D] rounded-full opacity-20 animate-pulse"></div>
                        <div class="relative w-10 h-10 bg-[#FFAA0D] rounded-full flex items-center justify-center shadow-lg shadow-[#FFAA0D]/40">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Asignar visita de campo</h3>
                        <p class="text-sm text-gray-500 leading-tight">Se asignará esta solicitud a inspección</p>
                    </div>
                </div>

                <button @click="openVisitaCampo = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-5">
                <p class="text-gray-700 text-base">
                    ¿Está seguro que desea actualizar esta solicitud <span class="font-bold text-gray-900" x-text="solicitud.no_solicitud"></span>?
                </p>

                <div class="mt-3 bg-amber-50 border-l-4 border-[#FFAA0D] p-3 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-[#FFAA0D]" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-900">
                                Una vez emitida, el estado cambiará a <strong>"Visita asignada"</strong> y el trámite continuará.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">
                <button @click="openVisitaCampo = false"
                        class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all order-2 sm:order-1">
                    No, cancelar
                </button>


                <button @click="Livewire.dispatch('peticionCampo', { id: solicitud.id });"

                        class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-[#FFAA0D] hover:bg-[#E6990C] rounded-xl shadow-lg shadow-amber-200 transition-all transform active:scale-95 order-1 sm:order-2">
                    Sí, asignar visita
                </button>


            </div>
        </div>
    </div>
</div>

   <!-- MODAL PARA AUTORIZAR LA SOLICITUD-->
 <div x-show="openPorAutorizar"
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4">

    <div class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm"
         @click="openPorAutorizar = false">
    </div>

    <div x-show="openPorAutorizar"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden">

        <div class="h-2 w-full" style="background-color:#4564EE;"></div>

        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                   <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                        style="background-color:#E8EBFD;"> <svg class="h-6 w-6" style="color:#4564EE" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">
                            Iniciar proceso de autorización
                        </h3>
                    </div>
                </div>

                <button @click="openPorAutorizar = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-5">
                <p class="text-gray-700 text-base leading-relaxed">
                    ¿Está seguro que desea cambiar el estado de la solicitud 
                    <span class="font-bold text-[#4564EE]" x-text="'#' + solicitud.no_solicitud"></span> 
                    a <span class="font-bold text-gray-900">“Por Autorizar”</span>?
                </p>

                <div class="mt-4 bg-blue-50 border-l-4 border-[#4564EE] p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-[#4564EE]" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                Esta acción indica que la revisión ha finalizado. La solicitud quedará lista para su <strong>emisión y firma posterior</strong>.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-8">
                <button @click="openPorAutorizar = false"
                        class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition-all order-2 sm:order-1">
                    Cancelar
                </button>

                <button @click="Livewire.dispatch('peticionPorAutorizar', { id: solicitud.id });"
                        class="px-6 py-2.5 text-sm font-bold text-white bg-[#4564EE] hover:bg-[#3651D1] rounded-xl shadow-lg shadow-blue-200 transition-all transform active:scale-95 order-1 sm:order-2">
                    Confirmar y enviar
                </button>
            </div>
        </div>
    </div>
</div>
</div>





</div>





<!-- creacion del blade para ver la solicitud -->

</x-interno-layout>
