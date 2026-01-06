<x-interno-layout :breadcrumb="[
    [
        'name' => 'Dashboard',
        'url' => route('interno.consulta.index')
    ],
    [
        'name' => 'Solicitudes'
    ]
]">


@livewire('solicitud-table')
</x-interno-layout>
