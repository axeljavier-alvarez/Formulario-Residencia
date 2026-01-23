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

        openPreview: false,
        imgSource: '',
        imagenActiva:null,
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
        openAceptar: false,
        observaciones: '',
        openDocs: false,
        
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

    x-on:solicitud-por-autorizar.window="
        openAceptar = false;
        open = false;
    "

     x-init="
                initEditor();        
        
    "


    

    @open-modal-visita.window="
        open = true;
        solicitud = $event.detail.solicitud;
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
     class="max-w-[95vw] max-h-[95vh] w-auto h-auto object-contain rounded-lg shadow-2xl"
>
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
<div x-show="open"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 bg-gray-900/35 backdrop-blur-sm transition-opacity z-50"
    @click="open = false">

</div>
        



<!-- MODAL -->
 
    
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



                <!-- VISITA DE CAMPO ASIGNADA -->
  <!-- ACORDEÓN VISITA DE CAMPO -->
    <div 
        x-show="solicitud.estado?.nombre === 'Visita asignada'"
        x-transition
        class="mt-10 border border-gray-200 rounded-xl shadow-sm bg-white"
    >

        <!-- HEADER ACORDEÓN -->
        <button 
            @click="openDocs = !openDocs" 
            class="w-full flex justify-between items-center px-6 py-4 bg-[#FFFBEB] hover:bg-[#FEF3C7] rounded-t-xl transition-colors"
        >
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-bold text-gray-800 uppercase text-xs tracking-widest">
                    Resultados de la visita de campo
                </span>
            </div>

            <!-- FLECHA QUE GIRA -->
            <svg :class="{'rotate-180': openDocs}" class="w-5 h-5 text-gray-500 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button

        <!-- CONTENIDO DESPLEGABLE -->
        <div x-show="openDocs" x-transition class="px-6 py-4 space-y-6">

            <!-- Observaciones -->
            <div class="flex items-center gap-2 mb-2">
                <span class="p-2 bg-amber-50 rounded-lg mr-2 text-amber-600">
                                    📝
                </span>
                <h4 class="font-bold text-gray-800 uppercase text-sm tracking-wider">Observaciones de la visita</h4>
            </div>
            <textarea
                id="editor"
                rows="4"
                placeholder="Ingrese observaciones..."
                class="w-full rounded-lg border border-gray-300 p-3 text-sm
                focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
            </textarea>

            <!-- Fotografías -->
            <div class="flex items-center gap-2 mb-2">
                <span class="p-2 bg-teal-50 rounded-lg mr-2 text-teal-600">📸</span>

                <h4 class="font-bold text-gray-800 uppercase text-sm tracking-wider">Fotografías</h4>
            </div>

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
                        const file = $event.target.files[0];
                        Livewire.find(livewireId)
                        .upload('fotos', file, ()=> {
                            fotosSeleccionadas.push({
                                url: URL.createObjectURL(file)
                            });
                        });
                        mostrarInput = false;
                        $event.target.value = '';
                    "
                >
            </div>

            <!-- BOTÓN AGREGAR FOTO -->
            <button 
                x-show="!mostrarInput" 
                @click="mostrarInput = true"
                class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700"
            >
                Agregar otra foto
            </button>

            <!-- GALERÍA DE FOTOS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                <template x-for="(foto, index) in fotosSeleccionadas" :key="index">
                    <div class="relative bg-gray-100 rounded-lg overflow-hidden border shadow-sm group">
                        <img :src="foto.url"
                             @click="$dispatch('preview-foto', { url: foto.url })"
                             class="w-full h-48 object-contain bg-white cursor-zoom-in hover:opacity-90 transition">
                        <button @click="fotosSeleccionadas.splice(index, 1)"
                                class="absolute top-2 right-2 bg-white/90 hover:bg-red-600 text-red-600 hover:text-white rounded-full p-1.5 shadow transition-all"
                                title="Eliminar foto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>

        </div>
    </div>

           


                <!-- VISITA DE CAMPO REALIZADA -->

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
                                <div class="p-2 bg-emerald-600 text-white rounded-lg">✔</div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-emerald-700">Estado</p>
                                    <p class="font-black text-emerald-900">Visita realizada</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 border border-blue-200">
                                <div class="p-2 bg-blue-600 text-white rounded-lg">📍</div>
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
                                    📝
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
                                                                    <span class="p-2 bg-teal-50 rounded-lg mr-2 text-teal-600">📸</span>
                                                                    <h4 class="font-bold text-gray-800 uppercase text-sm tracking-wider">Evidencia fotográfica</h4>
                                                                </div>
                                                                <template x-if="solicitud.fotos && solicitud.fotos.length > 0">
                                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                                        <template x-for="foto in solicitud.fotos" :key="foto.id">
                                                                            <div class="group relative aspect-video rounded-xl overflow-hidden border shadow-sm cursor-pointer" @click="$dispatch('preview-foto', { url: '/storage/' + foto.ruta })">
                                                                                <img :src="'/storage/' + foto.ruta" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" loading="lazy">
                                                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                                                                    <span class="text-white text-xs font-bold uppercase tracking-widest">Ver evidencia</span>
                                                                                </div>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </template>
                                    </div>


                    </div>



                        

                      


             </div>



                                <!-- BOTONES FINALES -->
                                    <div class="mt-12 pt-6 border-t border-gray-100">
                                                <div class="flex flex-col md:flex-row items-center justify-between gap-4">

                                                    <button type="button" 
                                                        x-show="solicitud.estado?.nombre === 'Visita asignada'"
                                                        @click="open = false"
                                                        class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl
                                                            bg-gray-200 px-10 py-3.5 text-sm font-black text-gray-700
                                                            shadow-xl shadow-gray-300/30
                                                            hover:bg-gray-300 hover:shadow-gray-400/40
                                                            transition-all transform active:scale-95">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        CERRAR
                                                    </button>

                                                    <button type="button"
                                                        x-show="solicitud.estado?.nombre === 'Visita asignada'"
                                                        @click="openVisitaAsignada = true"
                                                        class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl
                                                            bg-teal-600 px-10 py-3.5 text-sm font-black text-white
                                                            shadow-xl shadow-teal-400/30
                                                            hover:bg-teal-700 hover:shadow-teal-500/40
                                                            transition-all transform active:scale-95">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        ENVIAR VISITA DE CAMPO
                                                    </button>

                                                <button type="button"
                                                        x-show="solicitud.estado?.nombre === 'Visita realizada'"
                                                        @click="open = false"
                                                        class="w-full sm:w-auto ml-auto inline-flex items-center justify-center rounded-xl
                                                            bg-emerald-600 px-10 py-3.5 text-sm font-black text-white
                                                            shadow-xl shadow-emerald-400/30
                                                            hover:bg-emerald-700 hover:shadow-emerald-500/40
                                                            transition-all transform active:scale-95">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        ACEPTAR
                                                    </button>

                                                </div>
                                            </div>

                                                    






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
        openAceptar = false;
        open = false;
        Livewire.dispatch('peticionPorAutorizar', { id: solicitud.id });
    "
        class="px-4 py-2 text-sm font-bold text-white rounded-lg bg-green-600"
    >
        Aceptar solicitud
    </button>

    </div>

    </div>

</div>


</div>






<!-- NUEVO MODAL -->



</div>



</x-interno-layout>
