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
</x-interno-layout>
