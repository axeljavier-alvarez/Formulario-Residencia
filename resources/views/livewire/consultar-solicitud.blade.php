<div
    x-data="{ openModal: false }"
    x-effect="if (@js($solicitud)) { openModal = true }"
    class="px-4 py-12 -mt-5 bg-[#F3F4F6] min-h-screen font-sans"
>

    {{-- CONTENEDOR PRINCIPAL --}}
    <div class="max-w-2xl mx-auto p-4 md:p-0">

        {{-- FORMULARIO --}}
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200">
            <div class="p-8 md:p-12">

                {{-- TÍTULO --}}
                <div class="text-center mb-10">
                    <h1 class="text-2xl md:text-3xl font-black text-[#2563EB] tracking-tight mb-2">
                        CONSULTA DE ESTADO
                    </h1>
                    <div class="h-1 w-20 bg-[#2563EB] mx-auto rounded-full"></div>
                </div>

                {{-- ICONO --}}
                <div class="mb-10 relative">
                    <div class="absolute inset-0 bg-blue-50 rounded-full scale-150 blur-3xl opacity-50"></div>
                    <img
                        src="{{ asset('imagenes/icono_muni.png') }}"
                        alt="Icono"
                        class="w-28 md:w-36 mx-auto relative drop-shadow-xl"
                    >
                </div>

                {{-- ALERTA --}}
                <div class="mb-8 flex items-center gap-3 bg-[#FEF7DC] border border-yellow-200 p-4 rounded-2xl text-yellow-800 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="font-medium">Debe ingresar los datos exactos que colocó en su solicitud.</p>
                </div>

                {{-- INPUTS --}}
                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1 tracking-widest group-focus-within:text-[#2563EB] transition-colors">
                            Número de DPI / CUI
                        </label>
                        <input
                            type="text"
                            wire:model.defer="cui"
                            placeholder="0000 00000 0000"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 text-slate-700 font-bold focus:border-[#2563EB] focus:bg-white transition-all outline-none shadow-sm placeholder:text-slate-300"
                        >
                    </div>

                    <div class="group">
                        <label class="block text-xs font-black uppercase text-slate-500 mb-2 ml-1 tracking-widest group-focus-within:text-[#2563EB] transition-colors">
                            Número de solicitud
                        </label>
                        <input
                            type="text"
                            wire:model.defer="no_solicitud"
                            placeholder="Ej: SOL-12345"
                            class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 text-slate-700 font-bold focus:border-[#2563EB] focus:bg-white transition-all outline-none shadow-sm placeholder:text-slate-300"
                        >
                    </div>
                </div>

                {{-- ERROR --}}
                @if ($error)
                    <div class="mt-8 flex items-center justify-center gap-2 text-red-600 font-bold text-sm bg-red-50 p-4 rounded-2xl border border-red-100 animate-pulse">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $error }}
                    </div>
                @endif

                {{-- BOTONES --}}
                <div class="mt-10 flex flex-col md:flex-row gap-4">
                    <button
                        wire:click="consultar"
                        class="w-full md:w-2/3 bg-[#2563EB] hover:bg-blue-700 hover:shadow-blue-200 hover:shadow-lg text-white font-bold py-4 rounded-2xl shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 uppercase tracking-wider text-sm"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Consultar Ahora
                    </button>

                    <button
                        wire:click="limpiar"
                        type="button"
                        class="w-full md:w-1/3 bg-white border-2 border-slate-100 hover:bg-slate-50 text-slate-500 font-bold py-4 rounded-2xl transition-all active:scale-95 uppercase tracking-wider text-sm"
                    >
                        Limpiar
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div
        x-show="openModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
    >
        {{-- OVERLAY --}}
        <div
            class="absolute inset-0 bg-slate-900/80 backdrop-blur-md"
            @click="openModal = false; $wire.limpiarSolicitud();"
        ></div>

        {{-- CONTENIDO MODAL --}}
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            
            {{-- HEADER MODAL --}}
            <div class="bg-[#2563EB] px-8 py-6 flex justify-between items-center text-white">
                <div>
                    <h3 class="font-black uppercase tracking-tighter text-lg">Detalles de Consulta</h3>
                    <p class="text-blue-100 text-xs font-medium">Información actualizada en tiempo real</p>
                </div>
                <button
                    @click="openModal = false; $wire.limpiarSolicitud();"
                    class="bg-white/20 hover:bg-white/30 h-10 w-10 rounded-full flex items-center justify-center transition-colors"
                >
                    <span class="text-2xl leading-none">&times;</span>
                </button>
            </div>

            {{-- BODY --}}
            @if ($solicitud)
                <div class="p-8">
                    
                    {{-- ESTADO ACTUAL CARD --}}
                    <div class="bg-[#ECFDF5] border border-green-100 rounded-2xl p-6 mb-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Solicitante</p>
                                <p class="text-xl font-bold text-slate-800">{{ $solicitud->nombres }} {{ $solicitud->apellidos }}</p>
                            </div>
                            <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-green-200">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 text-center">Estado Actual</p>
                                <p class="text-[#2563EB] font-black uppercase text-center tracking-tight">
                                    {{ $solicitud->estado->nombre }}
                                </p>
                            </div>
                        </div>
                    </div>



                  


                    {{-- LÍNEA DE PROGRESO --}}
                    <div class="relative px-2">
                        <div class="mb-6 flex items-center gap-2">
                            <span class="flex h-2 w-2 rounded-full bg-[#2563EB]"></span>
                            <h4 class="text-xs font-black uppercase tracking-widest text-slate-400">Progreso de la solicitud</h4>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative">
                            @php
                                $nombreEstadoActual = $solicitud->estado->nombre;                                
                                // Lista de estados que queremos mostrar en los puntitos
                                $estadosVisibles = $estados->filter(fn($e) => !in_array($e->nombre, ['Visita asignada', 'Visita realizada', 'Previo', 'Rechazado']))->values();
                                // LÓGICA DE PROGRESO:
                                // Si el estado es Previo o Rechazado, el índice es -1 (ningún check se marca)
                                if (in_array($nombreEstadoActual, ['Previo', 'Rechazado'])) {
                                    $estadoActualIndex = -1; 
                                } else {
                                    $estadoActualIndex = $estadosVisibles->search(function($e) use ($solicitud, $nombreEstadoActual) {
                                        // Si está en visita, marcamos hasta 'Analisis'
                                        if (in_array($nombreEstadoActual, ['Visita asignada', 'Visita realizada'])) {
                                            return $e->nombre === 'Analisis';
                                        }
                                        return $e->id === $solicitud->estado_id;
                                    });
                                }
                            @endphp

                            @foreach($estadosVisibles as $index => $estado)
                                @php $completado = $index <= $estadoActualIndex; @endphp
                                <div class="flex flex-col items-center text-center group">
                                    <div class="relative mb-3">
                                        {{-- Línea conectora --}}
                                        @if(!$loop->last)
                                        <div class="hidden md:block absolute top-1/2 left-full w-full h-0.5 -translate-y-1/2 {{ $index < $estadoActualIndex ? 'bg-green-500' : 'bg-slate-100' }}"></div>
                                        @endif
                                        
                                        <div class="relative z-10 w-12 h-12 rounded-2xl flex items-center justify-center transition-all duration-500 {{ $completado ? 'bg-green-500 shadow-lg shadow-green-100 text-white rotate-3' : 'bg-slate-100 text-slate-400' }}">
                                            @if($completado)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                <span class="font-black text-lg">{{ $index + 1 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase leading-tight {{ $completado ? 'text-slate-800' : 'text-slate-400' }}">
                                        {{ $estado->nombre }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        

                        {{-- MENSAJES ESPECIALES --}}
                        {{-- MENSAJES ESPECIALES ACTUALIZADO --}}
@php
    // Definimos el icono y el color según el estado
    $configEstado = match($solicitud->estado->nombre) {
        'Visita asignada' => [
            'mensaje' => "Su solicitud tiene una visita de campo programada.",
            'color' => 'bg-blue-50 border-blue-100 text-blue-800',
            'svg' => '<svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
        ],
        'Visita realizada' => [
            'mensaje' => "La visita de campo ha sido completada satisfactoriamente.",
            'color' => 'bg-[#ECFDF5] border-green-100 text-green-800',
            'svg' => '<svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        ],
        default => null
    };
@endphp
@if($configEstado)
    <div class="mt-8 flex items-center gap-4 p-5 rounded-2xl border {{ $configEstado['color'] }}">
        <div class="flex-shrink-0 bg-white p-2 rounded-xl shadow-sm">
            {!! $configEstado['svg'] !!}
        </div>
        <p class="text-sm font-bold leading-tight">
            {{ $configEstado['mensaje'] }}
        </p>
    </div>
@endif
  <!-- NUEVOS mensajes -->
                    @if($solicitud->estado->nombre === 'Rechazado')
                            @php
                                // Buscamos el último evento de rechazo en la bitácora
                                $bitacoraRechazo = $solicitud->bitacoras
                                    ->where('evento', 'CAMBIO DE ESTADO: Rechazado')
                                    ->last();
                            @endphp

                            <div class="mt-6 bg-red-50 border-l-4 border-red-500 p-5 rounded-r-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="bg-white p-2 rounded-xl shadow">
                                        <i class="fas fa-times-circle text-red-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-red-600 font-black uppercase text-sm">
                                            Solicitud Rechazadaaaa
                                        </h3>
                                        
                                        {{-- Mostramos la descripción de la bitácora si existe, si no, las observaciones de la solicitud --}}
                                        <p class="text-red-700 text-sm mt-1">
                                            @if($bitacoraRechazo)
                                                <span class="font-bold">Motivo:</span> {{ $bitacoraRechazo->descripcion }}
                                            @else
                                                {{ $solicitud->observaciones ?? 'Su solicitud fue rechazada por el administrador.' }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @if($solicitud->estado->nombre === 'Previo')
                        <div class="mt-6 bg-orange-50 border-l-4 border-orange-500 p-5 rounded-r-2xl">
                            <div class="flex items-center gap-3">
                                <div class="bg-white p-2 rounded-xl shadow">
                                    <i class="fas fa-exclamation-triangle text-orange-500 text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-orange-600 font-black uppercase text-sm">
                                        Acción Requerida (Previo)
                                    </h3>
                                    <p class="text-orange-700 text-sm mt-1">
                                        Debe corregir o subir nuevamente los siguientes documentos:
                                    </p>
                                    <p class="font-bold text-orange-800 mt-2 text-xs italic">
                                        {{ $solicitud->observaciones }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($solicitud->estado->nombre === 'Previo')
                        <div class="mt-8 p-6 bg-white border-2 border-dashed border-orange-200 rounded-3xl">
                            <h4 class="text-gray-800 font-black flex items-center gap-2 mb-4">
                                <i class="fas fa-cloud-upload-alt text-orange-500"></i>
                                DOCUMENTOS A ARREGLAR
                            </h4>

                            <p class="text-xs text-gray-500 italic">
                                Cargue los archivos solicitados por el revisor para procesar su solicitud nuevamente.
                            </p>
                        </div>
                    @endif
                    </div>
                    <button
                        @click="openModal = false; $wire.limpiarSolicitud();"
                        class="mt-10 w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black rounded-2xl transition-colors uppercase text-xs tracking-widest"
                    >
                        Cerrar Ventana
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>