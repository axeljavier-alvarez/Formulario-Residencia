<x-interno-layout :breadcrumb="[
    ['name' => 'Dashboard', 'url' => route('interno.dashboard.index')],
    ['name' => 'Análisis de documentos']
]">

<style>
    /* 1. Forzamos que cada ítem de la leyenda sea un contenedor único */
    .apexcharts-legend-series {
        display: flex !important;
        align-items: center !important;
        margin-bottom: 6px !important;
    }
    .apexcharts-legend-marker {
        display: none !important; /* Escondemos el marcador original */
    }
    .apexcharts-legend-text {
        color: transparent !important;
        font-size: 0 !important;
    }
</style>

<div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">
    
    <div class="mb-10">
        @livewire('dashboard-estados')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-blue-50/30 rounded-2xl shadow-sm border-2 border-blue-100 overflow-hidden transition-all hover:shadow-lg">
            <div class="p-5 border-b border-blue-100 bg-white/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Distribución de Solicitudes</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Estado actual de expedientes</p>
                </div>
                <div class="h-10 w-10 bg-blue-500 text-white rounded-xl flex items-center justify-center shadow-sm">
                    <i class="fas fa-chart-pie"></i>
                </div>
            </div>
            <div class="p-6">
                <div id="chartEstados" class="min-h-[320px]"></div>
            </div>
        </div>

       
        <div class="bg-blue-50/40 rounded-2xl shadow-sm border border-blue-100 overflow-hidden transition-all hover:shadow-lg">
    <div class="p-5 border-b border-blue-100 bg-white/50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-gray-800">Visitas de Campo por Zona</h3>
            <p class="text-xs text-blue-600/70 uppercase tracking-wider font-semibold">Productividad regional</p>
        </div>
        <div class="h-10 w-10 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-sm">
            <i class="fas fa-map-location-dot"></i>
        </div>
    </div>

    <div class="p-6">
        @livewire('dashboard-visitas-zona')
        
        <div id="chartZonas" class="min-h-[320px] mt-4"></div>
    </div>
</div>

    </div>
</div>

@push('js')
<script>
    const globalFont = 'Inter, ui-sans-serif, system-ui, -apple-system, sans-serif';

    // Estilos dinámicos para limpiar las leyendas nativas de ApexCharts
    const style = document.createElement('style');
    style.innerHTML = `
        .apexcharts-legend-series {
            display: flex !important;
            align-items: center !important;
            margin-bottom: 6px !important;
            cursor: pointer;
        }
        .apexcharts-legend-marker {
            display: none !important;
        }
        .apexcharts-legend-text {
            color: transparent !important;
            font-size: 0 !important;
            margin-left: 0 !important;
            padding-left: 0 !important;
        }
    `;
    document.head.appendChild(style);

    document.addEventListener('DOMContentLoaded', function(){
        
        // --- 1. CONFIGURACIÓN GRÁFICA CIRCULAR (ESTADOS) ---
        // --- 1. CONFIGURACIÓN GRÁFICA CIRCULAR (ESTADOS) ---
        const optionsEstados = {
            chart: {
                type: 'donut',
                height: 380,
                fontFamily: globalFont,
                toolbar: { show: false },
                animations: { enabled: true }
            },
            // ESTO es lo que evita que se ponga oscuro al hacer click o pasar el mouse
            states: {
                normal: {
                    filter: { type: 'none', value: 0 }
                },
                hover: {
                    filter: { type: 'none', value: 0 }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: { type: 'none', value: 0 } // Aquí quitamos el oscurecimiento del click
                }
            },
            series: [],
            labels: [],
            colors: [],
            dataLabels: { enabled: false },
            legend: {
                show: true,
                position: 'right',
                horizontalAlign: 'left',
                useHTML: true,
                itemMargin: { vertical: 4 },
                formatter: function(seriesName, opts) {
                    const color = opts.w.config.colors[opts.seriesIndex];
                    // Usamos config.series para que el número sea persistente y no cambie a 0
                    const val = opts.w.config.series[opts.seriesIndex];
                    return `
                        <div style="display: flex; align-items: center; min-width: 140px; justify-content: space-between;">
                            <div style="display: flex; align-items: center;">
                                <div style="width: 12px; height: 12px; background-color: ${color}; border-radius: 50%; margin-right: 10px; flex-shrink: 0;"></div>
                                <span style="color: #475569; font-weight: 700; font-size: 14px;">${seriesName}</span>
                            </div>
                            <span style="color: ${color}; font-weight: 800; font-size: 14px; margin-left: 8px;">${val}</span>
                        </div>
                    `;
                }
            },
            plotOptions: {
                pie: {
                    expandOnClick: false, // Opcional: evita que el pedazo de dona "salte" al hacer click
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'TOTAL',
                                color: '#64748b',
                                fontWeight: 800,
                                // Fix para que el total central tampoco se pierda
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            }
        };

        const chartEstados = new ApexCharts(document.querySelector("#chartEstados"), optionsEstados);
        chartEstados.render();

        // --- 2. CONFIGURACIÓN GRÁFICA DE BARRAS (VISITAS) ---
        // --- 2. CONFIGURACIÓN GRÁFICA DE BARRAS (VISITAS) ---
        const optionsZonas = {
            chart: { 
                type: 'bar', 
                stacked: true, 
                height: 380, 
                fontFamily: globalFont, 
                toolbar: { show: false },
                events: {
                    legendClick: function(chartContext, seriesIndex, config) {
                        const w = chartContext.w;
                        const globals = w.globals;
                        const seriesNames = globals.seriesNames;
                        
                        // Verificamos si la serie clickeada es la única que está visible actualmente
                        const isOnlyVisible = globals.hiddenSeriesIndices.length === seriesNames.length - 1 && 
                                            !globals.hiddenSeriesIndices.includes(seriesIndex);

                        if (isOnlyVisible) {
                            // Si ya estaba aislada, mostramos TODAS de nuevo
                            seriesNames.forEach(name => chartContext.showSeries(name));
                        } else {
                            // Si no, aislamos la que se presionó
                            seriesNames.forEach((name, idx) => {
                                if (idx === seriesIndex) {
                                    chartContext.showSeries(name);
                                } else {
                                    chartContext.hideSeries(name);
                                }
                            });
                        }
                        return false; // Evita el comportamiento nativo de ApexCharts
                    }
                }
            },
            colors: ['#D97706', '#8B5CF6'],
            plotOptions: { 
                bar: { 
                    borderRadius: 6, 
                    columnWidth: '45%', 
                    dataLabels: { position: 'center' } 
                } 
            },
            dataLabels: { 
                enabled: true, 
                style: { fontSize: '12px', fontWeight: 800, colors: ['#fff'] },
                formatter: val => val > 0 ? val : ''
            },
            // tooltip: { enabled: false },
            series: [],
            xaxis: { categories: [], labels: { style: { colors: '#64748b', fontWeight: 500 } } },
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                useHTML: true,
                // IMPORTANTE: Mantenemos el total fijo usando la configuración inicial
                formatter: function(seriesName, opts) {
                    const color = opts.w.config.colors[opts.seriesIndex];
                    // Buscamos en config.series para que el dato sea estático y no cambie al filtrar
                    const dataOriginal = opts.w.config.series[opts.seriesIndex].data;
                    const total = dataOriginal.reduce((a, b) => a + (Number(b) || 0), 0);
                    
                    return `
                        <div style="display: flex; align-items: center; background: white; padding: 4px 15px; border-radius: 20px; border: 2px solid ${color}44; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div style="width: 10px; height: 10px; background-color: ${color}; border-radius: 3px; margin-right: 8px;"></div>
                            <span style="color: ${color}; font-weight: 700; font-size: 13px;">${seriesName}:</span>
                            <span style="color: ${color}; font-weight: 800; font-size: 14px; margin-left: 6px;">${total}</span>
                        </div>`;
                }
            }
        };
        const chartZonas = new ApexCharts(document.querySelector("#chartZonas"), optionsZonas);
        chartZonas.render();

        // LISTENERS LIVEWIRE
        window.addEventListener('updateChart', event => {
            chartEstados.updateOptions({ series: event.detail.series, labels: event.detail.labels, colors: event.detail.colors });
        });

        window.addEventListener('updateChartZonas', event => {
            chartZonas.updateOptions({ series: event.detail.series, xaxis: { categories: event.detail.labels } });
        });
    });
</script>
@endpush

</x-interno-layout>