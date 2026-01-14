<x-interno-layout :breadcrumb="[
    [
      'name' => 'Dashboard',
      'url' => route('interno.dashboard.index')
    ],
    [
    'name' => 'Analisis de documentos'
    ]

]">

<div class="my-6">
    @livewire('dashboard-estados')
</div>


</x-interno-layout>
