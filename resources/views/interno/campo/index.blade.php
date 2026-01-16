<x-interno-layout :breadcrumb="[
   [
    'name' => 'Dashboard',
    'url' => route('interno.dashboard.index')
   ],
   [
    'name' => 'Visita de campo'
   ]
]">




<div class="flex gap-2 mb-4">
    <button
        @click="Livewire.dispatch('filtrar-visitas', { estado: 'Visita asignada' })"
        class="px-4 py-2 rounded-lg font-bold bg-gray-200 text-gray-700">
        Visita Asignada
    </button>

    <button
        @click="Livewire.dispatch('filtrar-visitas', { estado: 'Visita realizada' })"
        class="px-4 py-2 rounded-lg font-bold bg-gray-200 text-gray-700">
        Visita Realizada
    </button>
</div>

@livewire('visita-campo-table')
<div
    x-data="{
       {{-- CODIGO PARA IMAGENES DE CAMPO --}}
        openPreview: false,
        imgSource: '',
        mostrarInput: true,
        fotosSeleccionadas: [],
        // guarda wire:id de componente livewire
        livewireId: null,
        init(){
            // espera a que todo el componente este renderizado
            this.$nextTick(() => {
               const el = document.querySelector('[wire\\:id]');
               if(el) {
                  this.livewireId = el.getAttribute('wire:id');
               }
            });
        },

        open: false,
        openVisitaAsignada: false,
        solicitud: {},
        step: 1,
        observaciones: '',

        initEditor() {
            // evitar crear multiples instancias
            if (window.visitaEditor) return;

            const el = document.querySelector('#editor');
            if (!el) return;

            ClassicEditor.create(el, {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'underline',
                        'link',
                        '|',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'undo',
                        'redo'
                    ],
                    shouldNotGroupWhenFull: true
                },
                simpleUpload: {
                    uploadUrl: '{{ route('interno.visita-campo.upload') }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            })
            .then(editor => {
                // guardar instancia global
                window.visitaEditor = editor;

                // ckeditor con alpine
                editor.model.document.on('change:data', () => {
                    this.observaciones = editor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });
        }
    }"
    @preview-foto.window="openPreview = true; imgSource = $event.detail.url"
   

    x-on:visita-realizada.window="
        openVisitaAsignada = false;
        open = false;
        observaciones = '';
        if (window.visitaEditor) window.visitaEditor.setData('');
    "

    x-init="
        $watch('step', value => {
            if (value === 2) {
                initEditor();
            }
        })
    "

    @open-modal-visita.window="
        open = true;
        solicitud = $event.detail.solicitud;
        step = 1;
    "
>


<!-- MODAL PARA ABRIR FOTO EN GRANDE -->
    <div
    x-show="openPreview"
    x-cloak
    @click="openPreview = false"
    class="fixed inset-0 z-[200] flex items-center justify-center
    bg-black bg-opacity-90 backdrop-blur-sm"
    @keydown.escape.window="openPreview = false"
    >
    <button @click="openPreview = false" class="absolute top-5
    right-5 text-white hover:text-red-400 transition-colors">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <img :src="imgSource"
    class="max-w-[90%] max-h-[90vh] object-contain shadow-2xl
    rounded-lg border border-white/20"> 
    </div>

<!-- MODAL PARA CONFIRMAR VISITA DE CAMPO -->
<div x-show="openVisitaAsignada"
     x-cloak
     class="fixed inset-0 z-[60] flex items-center justify-center">
     
    <div class="fixed inset-0 bg-black bg-opacity-50"
    @click="openVisitaAsignada = false">
    </div>

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
                        Confirmar visita de campo
                    </h3>
                </div>

                <button @click="openVisitaAsignada = false"
                              type="button"
                              class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors duration-200 focus:outline-none"
                              aria-label="Cerrar modal">
                          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                          </svg>
                </button>

              </div>

              <p class="font-bold text-blue-500 mt-2">
                    ¿Está seguro que desea confirmar esta visita de campo?
              </p>



                <div class="flex justify-end gap-3 mt-5">
                    <button
                    @click="openVisitaAsignada = false"
                    class="px-4 py-2 text-sm font-bold bg-gray-200 rounded-lg">
                    Cancelar
                    </button>

                    <button
                    @click="
                    Livewire.dispatch('visitaRealizada', {
                        id: solicitud.id,
                        observaciones: observaciones

                    });
                    " class="px-4 py-2 text-sm font-bold text-white rounded-lg
                    bg-teal-600"
                    >
                    Enviar visita de campo
                    </button>



                    </div>



               
    </div>
</div>



<!-- ABRIR MODAL PARA VISITA-->
<div x-show="open" x-cloak class="fixed inset-0 z-50
overflow-y-auto">


<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
@click="open = false">
</div>

<div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
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

   <!-- DATOS DEL STEP -->

   <!-- STEPPER -->
<div class="flex items-center justify-center mb-8">

    <!-- Paso 1 -->
    <div class="flex items-center">
        <button
            @click="step = 1"
            class="w-10 h-10 rounded-full border-2 font-bold transition
                   flex items-center justify-center"
            :class="step >= 1
               ? 'bg-[#FFAA0D] border-amber-500 text-white'
               : 'border-gray-300 text-gray-400'"
            >
            1
        </button>

        <div class="w-16 h-1"
             :class="step > 1 ? 'bg-amber-500' : 'bg-gray-300'"></div>
    </div>

    <!-- Paso 2 -->
    <div class="flex items-center">
        <button
            @click="step = 2"
            class="w-10 h-10 rounded-full border-2 font-bold transition
                   flex items-center justify-center"
            :class="step >= 2
                ? 'bg-[#FFAA0D] border-amber-500 text-white'
                : 'border-gray-300 text-gray-400'">
            2
        </button>

        <div class="w-16 h-1"
             :class="step > 2 ? 'bg-amber-500' : 'bg-gray-300'"></div>
    </div>

    <!-- Paso 3 -->
    <button
        @click="step = 3"
        class="w-10 h-10 rounded-full border-2 font-bold transition
               flex items-center justify-center"
        :class="step >= 3
            ? 'bg-[#FFAA0D] border-amber-500 text-white'
            : 'border-gray-300 text-gray-400'">
        3
    </button>

</div>



      <!-- 1. DATOS DE SOLICITUD -->

      <div class="grid grid-cols-1 gap-6">

         <div x-show="step === 1" x-transition>

            <div class="bg-gray-50 border border-gray-200
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




                      <div class="mt-4">
                                <h4 class="font-semibold text-gray-900">
                                    Dependientes:
                                </h4>

                                <div class="flex flex-wrap gap-2">
                                  <template x-if="solicitud.dependientes &&
                                  solicitud.dependientes.length > 0">
                                  <template x-for="dep in solicitud.dependientes"
                                  :key="dep.id">
                                    <span class="px-3 py-1 bg-green-50 text-green-700
                                    border border-green-200 rounded-full text-xs font-medium">

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


         </div>


         <!-- 2. BITACORA DE ESTA SOLICITUD -->

         <div x-show="step === 2" x-transition>

            <!-- MOSTRAR SOLO CUANDO ESTADO SEA VISITA ASIGNADA-->
           <div x-show="solicitud.estado?.nombre === 'Visita asignada'">

            <div class="bg-gray-50 border border-gray-200
                rounded-xl p-5 shadow-sm">
                    <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <span class="p-2 bg-gray-100 rounded-lg mr-2 text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h8M8 14h6m-2 6l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                                </span>

                                <h4 class="font-bold text-gray-800
                                uppercase text-sm tracking-wider">
                                Observaciones de Visita de campo
                                </h4>
                        </div>


                            <textarea 
                                id="editor"
                                rows="4"
                                placeholder="Ingrese observaciones..."
                                class="w-full rounded-lg border border-gray-300 p-3 text-sm
                                    focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                            </textarea>
 





                    </div>




                        <div>

                   <div class="flex items-center mb-2">
                     <span class="p-2 bg-gray-100 rounded-lg mr-2 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M3 7h3l2-3h8l2 3h3v11a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                     </span>

                     <h4 class="font-bold text-gray-800
                     uppercase text-sm tracking-wider">
                     Fotografías
                     </h4>
                     </div>

                     {{-- <input
                           type="file"
                           multiple
                           accept="image/*"
                           class="block w-full text-sm text-gray-600
                                 file:mr-4 file:py-2 file:px-4
                                 file:rounded-lg file:border-0
                                 file:text-sm file:font-semibold
                                 file:bg-gray-200 file:text-gray-700
                                 hover:file:bg-gray-300"
                     /> --}}

                   <!-- INPUT FILE -->
                   <div x-show="mostrarInput">
                    <input
                    type="file"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="block w-full text-sm text-gray-600
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-gray-200 file:text-gray-700
                    hover:file:bg-gray-300"

                    @change="
                    if (!livewireId) return;
                    // toma el primer archivo
                    const file = $event.target.files[0];
                    // busca componente livewire correcto
                    {{-- Livewire.find(livewireId)
                    .upload('fotos', file, ()=>{
                        // agrega nombre para mostrarlo
                        fotosSeleccionadas.push(file.name);
                        // ocultar input de subida
                        mostrarInput = false;
                        // resetear input para volver a usarlo
                        $event.target.value = '';
                    }); --}}

                    Livewire.find(livewireId)
                    .upload('fotos', file, ()=> {
                        fotosSeleccionadas.push({
                            name: file.name,
                            url: URL.createObjectURL(file),
                            mostrar: false
                        });


                    });
                    mostrarInput = false;
                    $event.target.value = '';
                    "
                    > 

                    </input>
                   </div>


                   <!-- BOTON PARA AGREGAR OTRA FOTO -->
                   <button
                   x-show="!mostrarInput"
                   @click="mostrarInput = true"
                   class="mt-3 px-4 py-2 bg-blue-600
                   text-white rounded-lg font-semibold hover:bg-blue-700"> 
                   Agregar otra foto
                   </button>

                   <!-- mostrar el listado de fotos -->
                   <div class="grid grid-cols-1 gap-3 mt-4">
                    <template x-for="(foto, index) in fotosSeleccionadas" :key="index">
                        <div class="border rounded-lg p-3 bg-white shadow-sm border-gray-200">
                            <div class="flex items-center justify-between gap-2 text-sm">
                                <div class="flex items-center gap-2 flex-1 cursor-pointer group"
                                @click="foto.mostrar = ! foto.mostrar">
                                

                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>

                                <span class="group-hover:text-teal-600 font-semibold transition-colors"
                                x-text="foto.name">
                                </span>
                         
                                </div>


                                <button
                                @click="fotosSeleccionadas.splice(index, 1)" class="p-1.5 hover:bg-red-100
                                rounded-full text-red-500 transition-colors" title="Eliminar Foto"
                                >

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        
                                </svg>

                                </button>
                            </div>
                            <!-- abrir foto -->
                            <div x-show="foto.mostrar"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="mt-4">

                            <div class="relative inline-block w-full
                            text-center bg-gray-50 rounded-lg border border-dashed border-gray-300 p-2">
                            <img :src="foto.url" @click="$dispatch('preview-foto', { url: foto.url })"
                            class="max-h-72 mx-auto rounded shadow-sm object-contain cursor-zoom-in">

                            <!-- botones -->
                            <div class="flex justify-center gap-2 mt-3">
                                <button
                                @click="$dispatch('preview-foto', { url: foto.url })"
                                type="button" class="inline-flex items-center px-3 py-1.5 bg-teal-600
                                hover:bg-teal-700 text-white text-xs font-bold rounded-md transition-colors shadow-sm"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver en grande
                                </button>

                                <button
                                @click="foto.mostrar = false"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-600 hover:bg-gray-700
                                text-white text-xs font-bold rounded-md transition-colors shadow-sm">
                                Ocultar
                                    
                                </button>
                            </div>
                            </div>
                            </div>
                        </div>
                    </template>
                   </div>
                                    

                                    


                                



               </div>




            </div>
 
           </div>
           <!-- MOSTRAR CUANDO YA SE COMPLETO LA VISITA -->
           <template x-if="solicitud.estado?.nombre === 'Visita realizada'">
            <h2>detalles de esta visita de campo</h2>
           </template>
                

         </div>


                  <!-- 3. OBSERVACIONES Y FOTOS -->
         <div x-show="step === 3" x-transition>
            
             <div class="bg-gray-50 border border-gray-200
         rounded-xl p-5 shadow-sm">
         <div class="flex items-center mb-3">

            <span class="p-2 bg-green-100 rounded-lg mr-2 text-green-600">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>

            </span>

            <h4 class="font-bold text-gray-800 uppercase text-sm
            tracking-wider">
            Historial de la solicitud
            </h4>

         </div>


         <div class="space-y-3 text-sm text-gray-600">
            <template x-if="solicitud.bitacoras && solicitud.bitacoras.length > 0">
               <template x-for="item in solicitud.bitacoras" :key="item.id">
                  <div class="bg-white border rounded-lg p-3">

                     <p x-show="item.evento">
                            <span class="font-semibold text-gray-900">
                                Evento
                            </span>
                            <span x-text="item.evento">

                            </span>
                     </p>


                      <template x-if="item.user">
                            <p>
                                <span class="font-semibold text-gray-900">
                                    Usuario
                                </span>
                                {{-- <span
                                    x-text="item.user ? item.user.name + ' ' + (item.user.lastname || '') : 'Sistema'"
                                    class="italic text-gray-500">
                                </span> --}}
                                <span
                                x-text="item.user.name"
                                class="italic text-gray-500"
                                >
                                </span>

                            </p>
                     </template>


                      <p>
                            <span class="font-semibold text-gray-900">
                                Fecha:
                            </span>
                            <span x-text="item.fecha_formateada">

                            </span>
                        </p>

                        <p>

                            <span class="font-semibold text-gray-900">
                                Detalle
                            </span>

                            <span x-text="item.descripcion">

                            </span>
                        </p>



                  </div>
               </template>
            </template>
         </div>



         </div>
            
         </div>
         <div class="flex justify-between mt-6">

            <button
               x-show="step > 1"
               @click="step--"
               class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 font-semibold">
               ← Anterior
            </button>

            <button
               x-show="step < 3"
               @click="step++"
               class="ml-auto px-4 py-2 rounded-lg bg-teal-600 text-white hover:bg-teal-700 font-semibold shadow-md hover:shadow-lg transition-colors duration-200">
               Siguiente →
            </button>



            <button
               @click="openVisitaAsignada = true"
               x-show="step === 3"
               class="ml-auto px-4 py-2 rounded-lg bg-teal-600 text-white hover:bg-teal-700 font-semibold">
               Enviar visita de campo
            </button>

         </div>




         </div>
      </div>


   </div>
</div>


</div>



</div>
</x-interno-layout>




