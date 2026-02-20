<x-interno-layout :breadcrumb="[
    ['name' => 'Dashboard', 'url' => route('interno.dashboard.index')],
    ['name' => 'Consulta de solicitudes']
]">
{{-- 
<div class="mb-6 bg-white p-6 rounded-2xl shadow-sm border border-gray-100" x-data="{ openColumns: false }">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div class="flex-shrink-0">
            <h2 class="text-xl font-black text-gray-800 tracking-tight">Gestión de Expedientes</h2>
            <p class="text-xs text-blue-500 font-bold uppercase tracking-widest mt-0.5">Panel de Control Interno</p>
        </div>

        <div class="flex flex-wrap items-center gap-4 flex-grow justify-end">
            <div class="relative w-full max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    placeholder="Buscar por nombre, DPI o expediente..." 
                    @input.debounce.400ms="$dispatch('custom-search', { term: $event.target.value })"
                    class="block w-full pl-11 pr-4 py-2.5 bg-gray-50 border-none text-sm font-semibold text-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-sm italic"
                >
            </div>

            <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">Estado:</span>
                <select 
                    @change="$dispatch('filter-estado', { estado: $event.target.value })"
                    class="bg-transparent border-none text-xs font-bold text-gray-700 focus:ring-0 cursor-pointer p-0 pr-6"
                >
                    <option value="">TODOS</option>
                    <option value="Pendiente">PENDIENTE</option>
                    <option value="Analisis">ANÁLISIS</option>
                    <option value="Autorizado">AUTORIZADO</option>
                    <option value="Rechazado">RECHAZADO</option>
                </select>
            </div>

            <div class="relative">
                <button 
                    @click="openColumns = !openColumns"
                    class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-black text-gray-600 hover:bg-gray-50 transition-all shadow-sm"
                >
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7" />
                    </svg>
                    COLUMNAS
                </button>

                <div 
                    x-show="openColumns" 
                    @click.away="openColumns = false"
                    x-transition
                    class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 p-4"
                >
                    <h4 class="text-[10px] font-black text-gray-400 uppercase mb-3">Visibilidad</h4>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" checked @change="$dispatch('toggle-column', { column: 'email' })" class="rounded text-blue-600 focus:ring-blue-500 w-4 h-4 border-gray-300">
                            <span class="text-xs font-bold text-gray-600 group-hover:text-blue-600">Contacto</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" checked @change="$dispatch('toggle-column', { column: 'created_at' })" class="rounded text-blue-600 focus:ring-blue-500 w-4 h-4 border-gray-300">
                            <span class="text-xs font-bold text-gray-600 group-hover:text-blue-600">Fecha Registro</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}


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


                            <!-- alerta -->
                <div 
                    x-data="{ visible: true }" 
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