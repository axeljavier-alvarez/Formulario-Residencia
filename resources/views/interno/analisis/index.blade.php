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
    open:false,
    solicitud: {},

    openRechazo: false,
    openAceptar: false,
    openVisitaCampo: false,

    openDocumento: false,
    documentoActual: null,

    observaciones: '',
    errorRechazo: null,

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

    x-on:error-rechazo.window="
    errorRechazo = $event.detail.mensaje
    "
    x-on:rechazo-exitoso.window="
        openRechazo = false;
        open = false;
        observaciones = '';
        errorRechazo = null;
    "

    x-on:solicitud-aceptada.window="
        openAceptar = false;
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
    {{-- x-show="open"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-cabellad="modal-title"
    role="dialog"
    aria-modal="true" --}}
>



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

      <div x-show="open" x-cloak class="fixed inset-0 z-50
      overflow-y-auto">

       <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          @click="open = false">
        </div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

              <!-- RECUADRO BLANCO -->
                {{-- <div x-show="open"
              x-cloak
              x-transition:enter="ease-out duration-300"
              x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
              x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"

              class="relative transform overflow-hidden rounded-lg
              bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full
              sm:max-w-4xl p-6"> --}}


              <!-- No solicitud y x responsivo -->
            {{-- <div class="border-b pb-3 mb-4 flex items-center justify-between
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


              </div> --}}


               <div x-show="open"
                x-cloak
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl p-6">

                <div class="bg-blue-200 text-gray-900 shadow-inner flex items-center justify-between relative
                            -mx-6 -mt-6 mb-6 px-6 py-4 border-b">

                    <h3 class="text-2xl font-bold" id="modal-title">
                        Solicitud No. <span x-text="solicitud.no_solicitud"></span>
                    </h3>

                    <button @click="open = false"
                            type="button"
                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors duration-200 focus:outline-none"
                            aria-label="Cerrar modal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>



              <!-- Datos generales -->
              <div class="grid grid-cols-1 gap-6">

                <!-- CONTENEDOR 1 -->
                <div class="bg-gray-50 border border-blue-200
                rounded-xl p-5 shadow-sm">

                <div class="flex items-center mb-3">
                  <span class="p-2 bg-blue-100 rounded-lg mr-2 text-blue-600">
                          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                  </span>

                  <h4 class="font-bold text-gray-800 uppercase text-sm tracking-wider">
                    Datos generales del solicitante
                  </h4>

                </div>

                <div class="space-y-3 text-sm text-gray-600">
                  <p>
                    <span class="font-semibold text-gray-900">
                      Nombre Completo
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
                      <span x-text="solicitud.fecha_registro_traducida">

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
                      : 'text-gray-600 font-normal ml-1'">
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

                        <span x-text="solicitud.zona?.nombre"></span>
                      </p>

                      <!-- DEPENDIENTES -->
                      <div class="mt-4">
                        <h4 class="font-semibold text-gray-900">
                          Dependientes
                        </h4>
                        <div class="flex flex-wrap gap-2">
                          <template x-if="solicitud.documentos && 
                          solicitud.documentos.find(d => d.tipo === 'carga')">
                          <div class="flex flex-wrap gap-2">
                            <template x-for="dep in solicitud.documentos.find(d => d.tipo === 'carga').dependientes" :key="dep.id">
                              <button
                              @click="documentoActual = dep; openDocumento = true;"
                              class="px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full
                              text-xs font-medium hover:bg-green-100 transition">
                              <span x-text="dep.nombre"></span>
                              </button>
                            </template>

                            <template x-if="solicitud.documentos.find(d => d.tipo === 'carga').dependientes.length === 0">
                              <span class="px-2 py-1 rounded-full text-xs font-bold bg-white border text-gray-500">
                                El usuario no ingreso dependientes
                              </span>
                            </template>
                          </div>
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

                          <!-- CONTENEDOR 2-->
                          <div class="bg-gray-50 border border-blue-200
                                      rounded-xl p-5 shadow-sm">

                                      <div class="flex items-center mb-3">
                                          <span class="p-2 bg-yellow-100 rounded-lg mr-2 text-yellow-600">
                                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                              </svg>
                                          </span>

                                          <h4 class="font-bold text-gray-800 uppercase text-sm tracking-wider">
                                              Documentos del solicitante
                                          </h4>
                                      </div>


                                      <!-- DOCUMENTOS DE TIPO NORMAL -->
                                      <div class="space-y-2 mt-3">
                                        <template x-if="solicitud.documentos && solicitud.documentos.length > 0">
                                          <template x-for="(doc, index) in solicitud.documentos" :key="index">
                                            
                                            <div>
                                              <template x-if="doc.tipo === 'normal'">
                                                <button
                                                @click="documentoActual = doc;
                                                openDocumento = true;"
                                                class="w-full flex items-center justify-between px-4 py-2 bg-yellow-50 border
                                                border-yellow-300 rounded-lg text-sm font-bold text-yellow-800 hover:bg-yellow-100
                                                transition mb-2">

                                                <span x-text="doc.titulo"></span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>


                                                </button>
                                              </template>
                                              <template x-if="doc.tipo === 'carga'">
                                                <div class="w-full px-4 py-2 bg-green-50 border border-green-300
                                                rounded-lg text-sm font-bold text-green-800">
                                                <div class="flex items-center mb-3">
                                                  <h4 class="font-bold" x-text="doc.titulo">

                                                  </h4>
                                                </div> 

                                                <div class="mt-1 space-y-1">
                                                  <template x-if="doc.dependientes && doc.dependientes.length > 0">
                                                    <template x-for="dep in doc.dependientes" :key="dep.id">
                                                    <button
                                                    @click="documentoActual = dep;
                                                    openDocumento = true;"
                                                    class="w-full text-left px-2 py-1 rounded bg-white border border-green-200
                                                    hover:bg-green-100 transition text-green-700 font-medium text-sm mb-1">
                                                    <span x-text="dep.nombre"></span>
                                                    </button>

                                                    
                                                    </template>

                                                  </template>

                                                  <template x-if="!doc.dependientes || doc.dependientes.length === 0">
                                                     <div class="bg-white/50 border border-green-200 rounded px-2 py-2 text-center">
                                                      <span class="text-xs font-bold text-green-600 uppercase tracking-tighter">
                                                        El usuario no ingresó dependientes
                                                      </span>
                                                  </div>

                                                  </template>
                                                </div>
                                                </div>

                                              </template>
          
                                            </div>
                                          </template>
                                        </template> 

                                      </div>


                            

                          








                            <!-- CARGAR ARRAY DE LOS DOCUMENTOS -->
                            {{-- <div class="space-y-1 mt-3">
                              <template x-if="solicitud.requisitos_por_tramite &&
                              solicitud.requisitos_por_tramite.length > 0">
                              <template x-for="req in
                              solicitud.requisitos_por_tramite" :key="req">
                              <div class="px-3 py-1 bg-yellow-50 text-yellow-800 border
                              border-yellow-200 rounded-lg text-sm font-bold">
                                <span x-text="req">

                                </span>
                              </div>
                              </template>
                              </template>



                              <template x-if="!solicitud.requisitos_por_tramite || solicitud.requisitos_por_tramite.length === 0">
                                  <span class="px-2 py-1 rounded-full text-xs font-bold bg-white border text-gray-500">
                                      N/A
                                  </span>
                              </template>


                            </div> --}}

                </div>


                <!-- CONTENEDOR 3 BOTONES -->
                  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <button
                      type="button"
                      {{-- @click="if(confirm('¿Está seguro que desea rechazar esta solicitud?')) {
                          Livewire.dispatch('peticionRechazar', { id: solicitud.id });
                          open = false;
                      }" --}}
                      @click="openRechazo = true"
                      class="inline-flex justify-center items-center rounded-lg px-6 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:opacity-90 active:scale-95"
                      style="background-color: #D63440;">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                      Rechazar
                  </button>

                    <div class="flex flex-col sm:flex-row gap-3">
                      <button type="button"

                      @click="openVisitaCampo = true "
                      class="inline-flex justify-center items-center rounded-lg px-6 py-2.5
                      text-sm font-bold text-black shadow-sm transition-all hover:bg-opacity-80
                      active:scale-95" style="background-color:  #FFAA0D;">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            Inspección de campo

                      </button>

                      <button type="button"
                        {{-- @click="if(confirm('¿Está seguro que desea aceptar esta solicitud?')) {
                          Livewire.dispatch('peticionEnProceso', { id: solicitud.id });
                          open = false;
                      }" --}}
                      @click="openAceptar = true"
                      class="inline-flex justify-center items-center
                      rounded-lg px-8 py-2.5 text-sm font-bold text-white shadow-sm
                      transition-all hover:bg-emerald-700 active:scale-95"
                      style="background-color: #059669;">
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                      Aceptar
                      </button>

                    </div>
                  </div>


              </div>


              </div>

      </div>




       <!-- MODAL DE RECHAZO -->
<div x-show="openRechazo" x-cloak class="fixed inset-0 z-60 flex items-center justify-center">
 <div class="fixed inset-0 bg-black bg-opacity-50"
    @click="openRechazo = false">
  </div>

    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">


      <div class="flex items-center justify-between">
 <div class="flex items-center gap-2">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none"
          viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-lg font-bold text-gray-800">
            Rechazar Solicitud
        </h3>




    </div>


     <button @click="openRechazo = false"
                            type="button"
                            class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors duration-200 focus:outline-none"
                            aria-label="Cerrar modal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
        </button>



      </div>








    <p class="font-bold mt-2 text-blue-500">
      ¿Está seguro que desea rechazar la solicitud?
    <p>
    <!-- MOSTRAR EL ERROR -->

    {{-- <div class="mt-2 text-sm text-red-600" wire:loading.remove>
      @if ($errorObservaciones)
      {{ $errorObservaciones }}
      @endif
    </div> --}}
    <p
    x-show="errorRechazo"
    x-cloak
    class="mt-2 text-sm text-red-600"
    x-text="errorRechazo">

    </p>




     <label class="block text-sm font-semibold text-gray-700 mb-2 mt-4">
      Observaciones:
    </label>

    <textarea
    x-model="observaciones"
    rows="4"
    class="w-full border rounded-lg p-2 text-sm focus:ring focus:ring-red-200"
    placeholder="Escriba el motivo del rechazo...">

    </textarea>
    <div class="flex justify-end gap-3 mt-5">
      <button
      @click="openRechazo = false"
      class="px-4 py-2 text-sm font-bold bg-gray-200 rounded-lg">
      Cancelar
      </button>

      <button
      @click="

     errorRechazo = null;
      Livewire.dispatch('peticionRechazar', {
        id: solicitud.id,
        observaciones: observaciones
      });
      {{-- observaciones='';
      openRechazo = false;
      open = false; --}}
      "
      class="px-4 py-2 text-sm font-bold text-white rounded-lg
      bg-red-600"
      >
      Confirmar rechazo
      </button>

    </div>
  </div>

  <div>

  </div>
</div>


  <!-- MODAL DE VISITA DE CAMPO -->
    <div x-show="openVisitaCampo" x-cloak class="fixed inset-0 z-60
    flex items-center justify-center">
    <div 
    class="fixed inset-0 bg-black bg-opacity-50"
    @click="openVisitaCampo = false">

    </div>

    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">



      <div class="flex items-center justify-between">


         <div class="flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 text-[#FFAA0D]"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4M7 21h10a2 2 0 002-2V7l-6-4H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
      </svg>


          <h3 class="text-lg font-bold text-gray-800">
              Solicitud a Visita de Campo
          </h3>
      </div>



      <button @click="openVisitaCampo = false"
                              type="button"
                              class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors duration-200 focus:outline-none"
                              aria-label="Cerrar modal">
                          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
      </button>




      </div>


      <p class="font-bold text-blue-500 mt-2">
        ¿Está seguro que desea mandar la solicitud a visita de campo?
      </p>

      <div class="flex justify-end gap-3 mt-5">
        <button
        @click="openVisitaCampo = false"
        class="px-4 py-2 text-sm font-bold bg-gray-200 rounded-lg">
        Cancelar
        </button>

        <button
        @click="
        Livewire.dispatch('peticionCampo', {
          id: solicitud.id
        });
        "
        class="px-4 py-2 text-sm font-bold text-white rounded-lg" style="background-color:  #FFAA0D;">
          Mandar a visita de campo
        </button>
      </div>

    </div>
    </div>



      <!-- MODAL DE ACEPTAR -->

  <div x-show="openAceptar" x-cloak class="fixed inset-0 z-60
  flex items-center justify-center">

      <div
        class="fixed inset-0 bg-black bg-opacity-50"
        @click="openAceptar = false"
      ></div>

    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">



      <div class="flex items-center justify-between">



         <div class="flex items-center gap-2">






     <svg xmlns="http://www.w3.org/2000/svg"
     class="h-6 w-6 text-green-600"
     fill="none"
     viewBox="0 0 24 24"
     stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" />
    </svg>



        <h3 class="text-lg font-bold text-gray-800">
            Aceptar Solicitud
        </h3>
    </div>


      <button @click="openAceptar = false"
                              type="button"
                              class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors duration-200 focus:outline-none"
                              aria-label="Cerrar modal">
                          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
      </button>



      </div>


      <p class="font-bold text-blue-500 mt-2">
        ¿Está seguro que desea aceptar está solicitud?
      </p>


       <div class="flex justify-end gap-3 mt-5">
      <button
      @click="openAceptar = false"
      class="px-4 py-2 text-sm font-bold bg-gray-200 rounded-lg">
      Cancelar
      </button>

      <button
      @click="
      Livewire.dispatch('peticionEnProceso', {
        id: solicitud.id
      });
      " class="px-4 py-2 text-sm font-bold text-white rounded-lg
      bg-green-600"
      >
      Aceptar solicitud
      </button>



    </div>

    </div>


  </div>







</div>





<!-- creacion del blade para ver la solicitud -->

</x-interno-layout>
