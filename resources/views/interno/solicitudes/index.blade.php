<x-interno-layout :breadcrumb="[
    [
        'name' => 'Dashboard',
        'url' => route('interno.consulta.index')
    ],
    [
        'name' => 'Consulta de solicitudes',
    ]
]">

    @livewire('solicitud-table')

   <!-- modal para ver acciones -->

    <div x-data="{ open: false, solicitud: {} }" 
     @open-modal-detalle.window="open = true; solicitud = $event.detail.solicitud"
     x-show="open"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-cabellad="modal-title" role="dialog" aria-modal="true">


   

    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>            


    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

   <div x-show="open"
   x-cloak
   x-transition:enter="ease-out duration-300"
   x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
   x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
   
   class="relative transform overflow-hidden rounded-lg
   bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full
   sm:max-w-4xl p-6">

   

   <div class="border-b pb-3 mb-4 flex items-center justify-between
   relative">
    <h3 class="text-2xl font-bold text-gray-900"
    id="modal-title">
    Solicitud No. 

    <span x-text="solicitud.no_solicitud">

    </span>
    </h3>


     <button @click="open = false" 
            type="button" 
            class="absolute top-0 right-0 p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors duration-200 focus:outline-none"
            aria-label="Cerrar modal">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    

   </div>

    <div class="grid grid-cols-1 gap-6">


            <div class="bg-gray-50 border border-gray-200
            rounded-xl p-5 shadow-sm">
            <div class="flex items-center mb-3">
                <span class="p-2 bg-blue-100 rounded-lg mr-2
                text-blue-600">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </span>

                <h4 class="font-bold text-gray-800 uppercase text-sm tracking-wider">
                    Datos generales del solicitante
                </h4>
            </div>
            <div class="space-y-3 text-sm text-gray-600">
                <p> 
                    <span class="font-semibold text-gray-900">
                        Nombre Completo: 
                    </span>

                    <span x-text="solicitud.nombres + ' ' + 
                    (solicitud.apellidos || '')">
                    </span>
                </p>

                <p>
                    <span class="font-semibold text-gray-900">
                        Email:
                    </span>

                    <span x-text="solicitud.email">

                    </span>

                </p>

                <p>
                    <span class="font-semibold text-gray-900">
                        Teléfono
                    </span>

                    <span x-text="solicitud.telefono">

                    </span>
                </p>

                <p>
                    <span class="font-semibold text-gray-900">
                        DPI/Cui
                    </span>

                    
                    <span x-text="solicitud.cui">

                    </span>
                </p>

                <p> 
                    <span class="font-semibold text-gray-900"> 
                        No. Solicitud
                    </span>

                    <span x-text="solicitud.no_solicitud">

                    </span>
                </p>


                <p>
                    <span class="font-semibold text-gray-900">
                        Fecha de registro
                    </span>
                    <span x-text="solicitud.created_at">

                    </span>
                </p>

                <p>
                    <span class="font-semibold text-gray-900">
                        Domicilio
                    </span>
                    <span x-text="solicitud.domicilio">
                    </span>
                </p>

                <p>
                    <span class="font-semibold text-gray-900">
                        Observaciones:
                    </span>
                    <span 
                        x-text="solicitud.observaciones ? solicitud.observaciones : 'N/A'"
                        :class="!solicitud.observaciones 
                            ? 'px-2 py-1 rounded-full text-xs font-bold bg-white border' 
                            : 'text-gray-600 font-normal ml-1'"
                    >
                    </span>
                </p>

                <p>
                    <span class="font-semibold text-gray-900">
                        Estado Actual:
                    </span>

                    <span 
                        x-text="solicitud.estado ? solicitud.estado.nombre : 'N/A'"
                        :class="!solicitud.estado 
                            ? 'px-2 py-1 rounded-full text-xs font-bold bg-white border' 
                            : 'text-gray-600 font-normal ml-1'"
                    >
                    </span>
                </p>

                <p>
                    <span class="font-semibold text-gray-900">
                        Zona:
                    </span>

                    <span
                    x-text="solicitud.zona.nombre"
                    >
                    </span>
                </p>


                <div class="mt-4">
                    <h4 class="font-semibold text-gray-900">
                        Dependientes:
                    </h4>

                    <div class="flex flex-wrap gap-2">
                      <template x-if="solicitud.dependientes &&
                      solicitud.dependientes.length > 0">
                      <template x-for="dep in solicitud.dependientes"
                      :key="dep.id">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700
                        border border-blue-200 rounded-full text-xs font-medium">

                        <span x-text="dep.nombres + ' ' + (dep.apellidos || '')">
                        </span>
                        </span>

                      </template>
                      </template>

                      <template x-if="!solicitud.dependientes ||
                      solicitud.dependientes.length === 0">
                      <span class="px-2 py-1 rounded-full text-xs font-bold 
                      bg-white border text-gray-500">
                       N/A
                      </span>
                      </template>
                    </div>
                </div>

                <p>
                    <span class="font-semibold text-gray-900">
                        Trámite:
                    </span>

                    <span 
                        class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded text-xs font-bold uppercase"
                        x-text="solicitud.requisitos_tramites?.[0]?.tramite?.nombre || 'General'"
                    >
                    </span>
                  
                </p>




            </div>
        </div>

    <!-- RECUADRO 2 -->
        <div class="bg-gray-50 border border-gray-200
        rounded-xl p-5 shadow-sm">

            <div class="flex items-center mb-3">
                <span class="p-2 bg-green-100 rounded-lg mr-2 text-green-600">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>

                </span>

                <h4 class="font-bold text-gray-800
                uppercase text-sm tracking-wider">
                Historial del trámite
                </h4>
            </div>

            <div class="space-y-3 text-sm text-gray-600">
                

                

                
            </div>

        </div>


   </div>

   <div class="mt-8 flex justify-end">
    <button @click="open = false"
    type="button"
    class="inline-flex justify-center
    rounded-md bg-green-600 px-8 py-2.5 text-sm
    font-semibold text-white shadow-sm hover:bg-green-500
    focus-visible:outline focus-visible:outline-2
    focus-visible:outline-offset-2 foucs-visible:outline-green-600
    transition-all">
    Aceptar
    </button>

   </div>




   </div>

   </div>
   
   </div>

</x-interno-layout>