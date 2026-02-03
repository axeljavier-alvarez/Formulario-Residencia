<div wire:poll.1s>

    <div class="text-center mb-8 -mt-6">
    <h2 class="text-4xl md:text-5xl font-black tracking-tighter uppercase italic leading-tight">
        <span class="inline-block px-1 bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-500">
            Estados de las
        </span>

        <span class="inline-block -px-2 bg-clip-text text-transparent bg-gradient-to-r from-green-600 to-lime-500">
            solicitudes
        </span>
    </h2>
    </div>



     <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-7 gap-5">
    @foreach($estadosTarjetones as $estado)
        @php
            // Definimos gradientes según el estado para darle más vida
            $gradient = match($estado->nombre) {
                'Pendiente'     => 'from-amber-400 to-yellow-500',
                'Analisis'      => 'from-cyan-400 to-blue-500', 
                'Por autorizar' => 'from-blue-500 to-indigo-600', 
                'Emitido'       => 'from-stone-400 to-gray-600', 
                'Autorizado'    => 'from-green-500 to-emerald-600', 
                'Previo'        => 'from-orange-400 to-red-500',
                'Rechazado'     => 'from-red-500 to-rose-700',
                default         => 'from-gray-400 to-gray-600',
            };

            $colorPrincipal = match($estado->nombre) {
                'Pendiente'     => '#FACC15',
                'Analisis'      => '#06B6D4', 
                'Por autorizar' => '#3B82F6', 
                'Emitido'       => '#A8A29E', 
                'Autorizado'    => '#22C55E', 
                'Previo'        => '#F97316',
                'Rechazado'     => '#EF4444',
                default         => '#6B7280',
            };

            $icon = match($estado->nombre) {
                'Pendiente'     => 'fa-hourglass-half',
                'Analisis'      => 'fa-magnifying-glass-chart',
                'Por autorizar' => 'fa-user-shield',
                'Emitido'       => 'fa-file-export',
                'Autorizado'    => 'fa-circle-check',
                'Previo'        => 'fa-list-check',
                'Rechazado'     => 'fa-circle-xmark',
                default         => 'fa-circle-question',
            };
        @endphp

        <div class="relative group bg-white rounded-3xl p-6 border border-gray-100 shadow-md transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 overflow-hidden">
            
            <div class="absolute -inset-1 bg-gradient-to-r {{ $gradient }} opacity-0 group-hover:opacity-10 transition duration-500 blur"></div>
            
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r {{ $gradient }}"></div>
            
            <div class="relative flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4 shadow-inner shadow-white transition-all duration-500 group-hover:scale-110" 
                     style="background-color: {{ $colorPrincipal }}20;"> 
                    <i class="fas {{ $icon }} text-2xl" style="color: {{ $colorPrincipal }}"></i>
                </div>

                <h3 class="text-[11px] font-black text-gray-500 uppercase tracking-[0.2em] text-center mb-1">
                    {{ $estado->nombre }}
                </h3>
                
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-black text-gray-900 leading-none">
                        {{ $estado->solicitudes_count }}
                    </span>
                </div>
                
                {{-- <div class="mt-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                    <span class="text-[9px] px-3 py-1 rounded-full text-white font-black uppercase tracking-tighter shadow-sm bg-gradient-to-r {{ $gradient }}">
                        Ver solicitudes
                    </span>
                </div> --}}
            </div>
        </div>
    @endforeach
    </div>



   {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
   @foreach($estadosTarjetones as $estado)
       @php
           $color = match($estado->nombre) {
                'Pendiente'      => '#FACC15',
                    'Analisis'       => '#06B6D4', 
                    'Por autorizar'  => '#3B82F6', 
                    'Emitido'        => '#C2A97E', 
                    'Autorizado'     => '#22C55E', 
                    'Previo'         => '#F97316',
                    'Rechazado'      => '#EF4444',
                    default          => '#6B7280',
           };

           $icon = match($estado->nombre) {
               'Pendiente'      => 'fa-hourglass-half',
                    'Analisis'       => 'fa-magnifying-glass-chart',
                    'Por autorizar'  => 'fa-user-shield',
                    'Emitido'        => 'fa-file-export',
                    'Autorizado'     => 'fa-circle-check',
                    'Previo'         => 'fa-list-check',
                    'Rechazado'      => 'fa-circle-xmark',
                    default          => 'fa-circle-question',
           };
       @endphp

       <div class="rounded-lg p-4 text-white flex flex-col items-center justify-center cursor-pointer transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl"
            style="background-color: {{ $color }}">
           <i class="fas {{ $icon }} text-3xl mb-2"></i>
           <span class="font-semibold text-lg text-center">{{ $estado->nombre }}</span>
           <span class="text-2xl font-bold mt-1">{{ $estado->solicitudes_count }}</span>
       </div>
   @endforeach
   </div> --}}
</div>