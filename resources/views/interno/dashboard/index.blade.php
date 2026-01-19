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



 <div class="my-6 grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Gráfica 1 -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-semibold mb-2 text-gray-700">
                Estados de solicitudes
            </h3>
            <div id="chartEstados"></div>
        </div>

        <!-- Gráfica 2 -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm font-semibold mb-2 text-gray-700">
                Solicitudes por tipo
            </h3>
            <div id="chartTipos"></div>
        </div>

    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Gráfica 1 - Donut
            new ApexCharts(document.querySelector("#chartEstados"), {
                chart: {
                    type: 'donut',
                    height: 260
                },
                series: [12, 8, 5, 3],
                labels: ['Pendiente', 'Aprobada', 'Rechazada', 'En proceso'],
                legend: {
                    position: 'bottom'
                }
            }).render();

            // Gráfica 2 - Barras
            new ApexCharts(document.querySelector("#chartTipos"), {
                chart: {
                    type: 'bar',
                    height: 260
                },
                series: [{
                    name: 'Solicitudes',
                    data: [15, 9, 6]
                }],
                xaxis: {
                    categories: ['Residencia', 'Construcción', 'Comercio']
                }
            }).render();

        });
    </script>
    @endpush

    

</x-interno-layout>
