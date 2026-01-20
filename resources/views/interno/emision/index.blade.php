<x-interno-layout :breadcrumb="[
   [
    'name' => 'Dashboard',
    'url' => route('interno.emision-constancia.index')
   ],
   [
    'name' => 'Emisión de constancias'
   ]
]">


@livewire('emision-constancias-table')


</x-interno-layout>