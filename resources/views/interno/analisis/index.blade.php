<x-interno-layout :breadcrumb="[
    [
      'name' => 'Dashboard',
      'url' => route('interno.consulta.index')
    ],
    [
    'name' => 'Analisis de documentos'
    ]
    
]">


@livewire('analisis-documentos-table')

<!-- CREACION DEL modal -->
<div
x-data="{ open:false, solicitud: {}}"
@open-modal-solicitud.window="open = true; solicitud = $event.detail.solicitud"
x-show="open"
x-cloak
class="fixed inset-0 z-50 overflow-y-auto"
aria-cabellad="modal-title" role="dialog" aria-modal="true"
> 

<div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"> 
</div>

</div>

<!-- creacion del blade para ver la solicitud -->

</x-interno-layout>