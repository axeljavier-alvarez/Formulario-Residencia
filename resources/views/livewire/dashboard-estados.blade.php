<div wire:poll.1s>

   <div class="grid grid-cols-1 sm:grid-cols-2 
   md:grid-cols-3 lg:grid-cols-6 gap-4">
   @foreach($estados as $estado)
   @php
       $color = match($estado->nombre){
                'Pendiente'        => '#FACC15',
                'En proceso'       => '#3B82F6',
                'Visita asignada'  => '#EAB308',
                'Visita realizada' => '#8B5CF6',
                'Completado'       => '#22C55E',
                'Cancelado'        => '#EF4444',
                default            => '#6B7280',
       };

       $icon = match($estado->nombre){
                'Pendiente'        => 'fa-clock',
                'En proceso'       => 'fa-spinner',
                'Visita asignada'  => 'fa-map-marker-alt',
                'Visita realizada' => 'fa-check-double',
                'Completado'       => 'fa-check-circle',
                'Cancelado'        => 'fa-times-circle',
                default            => 'fa-question-circle',
       };
   @endphp

   <div class="rounded-lg p-4 text-white flex flex-col
   items-center justify-center" 
   style="background-color: {{ $color }}">

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
