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
        let chart;

        // 1. Configuración inicial de la gráfica (Vacía)
        const options = {
            chart: {
                type: 'donut',
                height: 260
            },
            series: [], 
            labels: [],
            colors: [],
            legend: { position: 'bottom' },
            noData: { text: 'Cargando datos...' }
        };

        chart = new ApexCharts(document.querySelector("#chartEstados"), options);
        chart.render();

        // 2. Escuchar el evento de Livewire para actualizar los datos
        // Livewire v3 usa event.detail para los datos
        window.addEventListener('updateChart', event => {
            chart.updateOptions({
                series: event.detail.series,
                labels: event.detail.labels,
                colors: event.detail.colors
            });
        });
    });
</script>
@endpush

    

</x-interno-layout>
