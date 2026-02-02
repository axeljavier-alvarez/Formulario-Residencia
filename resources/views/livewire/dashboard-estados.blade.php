<div wire:poll.1s>


   <div class="text-center mb-8">
    <h2 class="inline-block text-4xl font-bold -mt-4 mb-2 text-green-600 tracking-wide">
        ESTADOS DE LAS SOLICITUDES
    </h2>

        <div class="mx-auto mt-2 h-2 w-full rounded"
            style="background-color: #83BD3F">
        </div>
    </div>




   <div class="grid grid-cols-1 sm:grid-cols-2 
   md:grid-cols-3 lg:grid-cols-6 gap-4">
   @foreach($estados as $estado)

   @if(in_array($estado->nombre, ['Visita asignada', 'Visita realizada']))
        @continue
   @endif
   @php
       $color = match($estado->nombre){
                'Pendiente'        => '#FACC15',
                // 'Visita asignada'  => '#D97706',
                // 'Visita realizada' => '#8B5CF6',
                'Por emitir'    => '#06B6D4',
                'Emitido'          => '#D6C19A', 
                'Por autorizar'    => '#3B82F6',
                'Autorizado'       => '#39FF14',
                'Completado'       => '#16A34A', 
                'Previo'           => '#F97316',
                'Cancelado'        => '#EF4444',
                default            => '#6B7280',
       };

       $icon = match($estado->nombre){
                'Pendiente'        => 'fa-clock',
                // 'Visita asignada'  => 'fa-map-marker-alt',
                // 'Visita realizada' => 'fa-check-double',
                'Por emitir'       => 'fa-file-circle-plus',
                'Emitido'          => 'fa-file-lines',  
                'Por autorizar'       => 'fa-user-check',
                'Autorizado'       => 'fa-circle-check', 
                 'Completado'       => 'fa-check-double',  
                'Previo'           => 'fa-arrows-rotate',
                'Cancelado'        => 'fa-times-circle',
                default            => 'fa-question-circle',
       };
   @endphp

   <div
        class="rounded-lg p-4 text-white flex flex-col
        items-center justify-center cursor-pointer
        transform transition-all duration-300 ease-out
        hover:-translate-y-2 hover:scale-105
        hover:shadow-2xl hover:brightness-110"
        style="background-color: {{ $color }}"
    >

   <i class="fas {{ $icon }} text-3xl mb-2"> </i>

   <span class="font-semibold text-lg">
    {{ $estado->nombre }}
   </span>

   <span class="text-2xl font-bold mt-1">
    {{ $estado->solicitudes_count }}
   </span>
   </div>
   @endforeach
   </div>
</div>
