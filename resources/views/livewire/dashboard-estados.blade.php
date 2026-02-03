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
            'Pendiente'      => '#FACC15',
            'Analisis'       => '#06B6D4', 
            'Por autorizar'  => '#3B82F6', 
            'Emitido'        => '#C2A97E', 
            'Autorizado'       => '#22C55E', 
            'Previo'           => '#F97316',
            'Rechazado'        => '#EF4444',
            default            => '#6B7280',
        };

        $icon = match($estado->nombre){
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
