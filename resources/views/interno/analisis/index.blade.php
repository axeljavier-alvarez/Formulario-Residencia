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

<!-- creacion del blade para ver la solicitud -->

</x-interno-layout>