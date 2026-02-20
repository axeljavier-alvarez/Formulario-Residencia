<x-interno-layout :breadcrumb="[
    ['name' => 'Dashboard', 'url' => route('interno.dashboard.index')],
    ['name' => 'Consulta de solicitudes']
]">

    @livewire('solicitud-table')

    <div x-data="{ 
        open: false, 
        openAbrirExpediente: false,
        solicitud: {} 
    }"
    @open-modal-detalle.window="open = true; solicitud = $event.detail.solicitud"
    @abrir-modal-expediente.window="openAbrirExpediente = true; solicitud = $event.detail.solicitud"
    @close-confirm.window="openAbrirExpediente = false"
    x-cloak>

        


    
    <div x-show="openAbrirExpediente"
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4">

    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
         @click="openAbrirExpediente = false">
    </div>

    <div x-show="openAbrirExpediente"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 relative overflow-hidden">

        <div class="h-2 w-full" style="background-color: #E6990C;"></div>

        <div class="p-6 bg-white">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="relative flex items-center justify-center w-12 h-12">
                        <div class="absolute inset-0 rounded-full opacity-20 animate-pulse" style="background-color: #E6990C;"></div>
                        <div class="relative w-10 h-10 rounded-full flex items-center justify-center shadow-lg shadow-[#E6990C]/30" style="background-color: #E6990C;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Análisis no iniciado</h3>
                        <p class="text-xs font-black uppercase tracking-widest" style="color: #E6990C;">Estado: Pendiente</p>
                    </div>
                </div>

                <button @click="openAbrirExpediente = false"
                        class="text-gray-400 hover:text-gray-600 transition-colors p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="mt-5">
                <p class="text-gray-600 text-base leading-relaxed">
                    El expediente <span class="font-bold text-gray-900" x-text="'#' + solicitud.no_solicitud"></span> no se ha abierto.
                </p>

                <div class="mt-4 border-l-4 p-4 rounded-r-xl shadow-sm border-[#E6990C]" style="background-color: #FFFFFF; border-style: solid;">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5" style="color: #E6990C;" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-700 leading-snug">
                                El analista responsable <strong>no ha abierto todavía el expediente</strong>.                                            
                                Podrá visualizar el detalle completo en cuanto el expediente sea abierto formalmente.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-8">
                <button @click="openAbrirExpediente = false"
                        class="w-full px-6 py-3 text-sm font-bold text-white rounded-xl shadow-lg shadow-[#E6990C]/30 transition-all transform active:scale-95 hover:brightness-110"
                        style="background-color: #E6990C;">
                    Entendido, cerrar
                </button>
            </div>
        </div>
    </div>
</div>


        <div x-show="open" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             role="dialog" aria-modal="true">
            
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 bg-gray-900/35 backdrop-blur-sm transition-opacity"
                 @click="open = false">
            </div>          

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="open"
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
                                <h3 class="text-xl font-bold text-white leading-tight">Detalle de Solicitud</h3>
                                <p class="text-blue-100 text-sm font-medium">No. <span x-text="solicitud.no_solicitud"></span></p>
                            </div>
                        </div>
                        <button @click="open = false" class="text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
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
                                        <label class="block text-[10px] uppercase font-bold text-gray-400">Nombre Completo</label>
                                        <p class="text-gray-900 font-semibold" x-text="solicitud.nombres + ' ' + (solicitud.apellidos || '')"></p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <label class="block text-[10px] uppercase font-bold text-gray-400">DPI / CUI</label>
                                        <p class="text-gray-900 font-mono" x-text="solicitud.cui"></p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <label class="block text-[10px] uppercase font-bold text-gray-400">Teléfono</label>
                                        <p class="text-gray-900" x-text="solicitud.telefono"></p>
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
                                    <span class="text-green-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                                    <h4 class="font-bold text-gray-800 uppercase text-xs tracking-widest">Historial</h4>
                                </div>
                                <div class="max-h-[300px] overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                                    <template x-if="solicitud.bitacoras && solicitud.bitacoras.length > 0">
                                        <template x-for="item in solicitud.bitacoras" :key="item.id">
                                            <div class="relative pl-4 border-l-2 border-blue-200 py-1">
                                                <div class="absolute -left-[9px] top-2 w-4 h-4 rounded-full bg-blue-500 border-2 border-white"></div>
                                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                    <div class="flex justify-between items-start mb-1">
                                                        <span class="text-xs font-bold text-gray-900 uppercase" x-text="item.evento"></span>
                                                        <span class="text-[10px] text-gray-400" x-text="item.fecha_formateada"></span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 italic" x-text="item.descripcion"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="!solicitud.bitacoras || solicitud.bitacoras.length === 0">
                                        <div class="text-center py-10 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                                            <p class="text-xs font-bold text-gray-400">Sin movimientos</p>
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

                        <div class="mt-10 flex justify-end pt-6 border-t border-gray-100">
                            <button @click="open = false" class="inline-flex items-center bg-green-600 px-10 py-3 text-sm font-bold text-white rounded-xl shadow-lg hover:bg-green-700 transition-all transform active:scale-95">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Aceptar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> </x-interno-layout>