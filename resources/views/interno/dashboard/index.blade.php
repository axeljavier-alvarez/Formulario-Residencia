<x-interno-layout :breadcrumb="[
    ['name' => 'Dashboard', 'url' => route('interno.dashboard.index')],
    ['name' => 'Análisis de documentos']
]">

<style>
    .apexcharts-legend-series {
        display: flex !important;
        align-items: center !important;
        margin-bottom: 4px !important;        
    }
    .apexcharts-legend-marker {
        display: none !important;
    }
    .apexcharts-legend-text {
        color: transparent !important;
        font-size: 0 !important;
    }
</style> 

<div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen">
    
    <div class="mb-10">
        @livewire('dashboard-estados')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-blue-50/30 rounded-2xl shadow-sm border-2 border-blue-100 overflow-hidden transition-all hover:shadow-lg">
            <div class="p-5 border-b border-blue-100 bg-white/50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Tipos de trámite</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-medium">Total de solicitudes recibidas por tramite</p>
                </div>
                <div class="h-10 w-10 bg-indigo-500 text-white rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-clipboard-list"></i>
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
   
   // misma tipografia
     const globalFont = 'Inter, ui-sans-serif, system-ui, -apple-system, sans-serif';

    const style = document.createElement('style');
    style.innerHTML = `
        .apexcharts-legend-series {
            display: flex !important;
            align-items: center !important;
            margin-bottom: 6px !important;
            cursor: pointer;
        }
            // ocultar cuadro y texto original
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
        
     
        const optionsEstados = {


            tooltip: {
                enabled: false,
            },

        responsive: [
    {
        breakpoint: 640, 
        options: {
            chart: {
                height: 450 
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                itemMargin: {
                    horizontal: 5,
                    vertical: 10
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%' 
                    }
                }
            }
        }
    }, 
    {
        breakpoint: 2000, 
        options: {
            chart: {
                height: 380
            },
            legend: {
                position: 'right',
                horizontalAlign: 'left'
            }
        }
    }
],

            
            chart: {
                type: 'donut',
                height: 380,
                fontFamily: globalFont,
                toolbar: { show: false },
                animations: { enabled: true }
            },


            


          
            series: [],
            labels: [],
            colors: [],
             noData: {
            text: 'Cargando datos...', 
             align: 'center',        
            verticalAlign: 'middle',
            style: {
                color: '#64748b',
                fontSize: '18px',
                fontFamily: globalFont
            }
        },

           // poner el porcentaje en la grafica
            dataLabels: { 
                enabled: true,

            },
            // leyenda personalizada
            legend: {
                show: true,
                position: 'right',
                horizontalAlign: 'left',
                useHTML: true,
                // itemMargin: { vertical: 4 },
             
                formatter: function(seriesName, opts) {

                    const val = opts.w.config.series[opts.seriesIndex];
                    const total = opts.w.config.series.reduce((a,b) => a+b, 0);

                    const percent = total ? ((val / total) * 100).toFixed(1) : 0;

                    const color = opts.w.config.colors[opts.seriesIndex];

                    const icon = (currentIcons && currentIcons[opts.seriesIndex])
                    ? currentIcons[opts.seriesIndex]
                    : 'fas fa-circle'; 
                    // const color = opts.w.config.colors[opts.seriesIndex];


                     return `
            <div style="display: flex; align-items: center; min-width: 160px; justify-content: space-between; padding: 2px 0;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 24px; text-align: center; margin-right: 8px;">
                        <i class="${icon}" style="color: ${color}; font-size: 14px;"></i>
                    </div>
                    <span style="color: #475569; font-weight: 600; font-size: 13px;">${seriesName}</span>
                </div>
                <div style="text-align: right;">
                    <span style="color: ${color}; font-weight: 800; font-size: 13px; display: block;">${percent}%</span>
                    <small style="color: #94a3b8; font-size: 10px; display: block;">${val} sol.</small>
                </div>
            </div>`;

                    // return `
                    // <div style="display: flex; align-items:center; min-width: 140px;
                    // justify-content: space-between;">
                    // <div style="display: flex; align-items: center;">
                        
                    //     <div style="width: 12px; height: 12px;
                    //     background-color: ${color}; border-radius: 50%; margin-right: 10px;
                    //     flex-shrink: 0;
                    //     ">
                    //     </div>
                    //     <span style="color: #475569; font-weight: 700;
                    //     font-size:14px;">
                    //     ${seriesName}
                    //     </span>    
                    // </div> 
                    // <span style="color: ${color}; font-weight: 800; font-size: 14px;
                    // margin-left: 8px;">
                    // ${percent}%
                    // </span>    
                    // </div> 
                    // `


                    // return `
                    //     <div style="display: flex; align-items: center; min-width: 140px; justify-content: space-between;">
                    //         <div style="display: flex; align-items: center;">
                    //             <div style="width: 12px; height: 12px; background-color: ${color}; border-radius: 50%; margin-right: 10px; flex-shrink: 0;"></div>
                    //             <span style="color: #475569; font-weight: 700; font-size: 14px;">${seriesName}</span>
                    //         </div>
                    //         <span style="color: ${color}; font-weight: 800; font-size: 14px; margin-left: 8px;">${val}</span>
                    //     </div>
                    // `;
                }
            },
            plotOptions: {
                pie: {
                    expandOnClick: false, 
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'TOTAL',
                                color: '#64748b',
                                fontWeight: 800,
                               
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


        // 2. grafica de barras
        const optionsZonas = {

           

            tooltip: {
                enabled: false,
            },

            
            chart: { 

                // grafica de barras apilada
                type: 'bar', 
                stacked: true, 
                height: 380, 
                fontFamily: globalFont, 
                toolbar: { show: false },
                events: {
                    // click normal aisla una serie normal muestra todas
                    legendClick: function(chartContext, seriesIndex, config) {
                        const w = chartContext.w;
                        const globals = w.globals;
                        const seriesNames = globals.seriesNames;
                        
                        const isOnlyVisible = globals.hiddenSeriesIndices.length === seriesNames.length - 1 && 
                                            !globals.hiddenSeriesIndices.includes(seriesIndex);

                        if (isOnlyVisible) {
                            seriesNames.forEach(name => chartContext.showSeries(name));
                        } else {
                            seriesNames.forEach((name, idx) => {
                                if (idx === seriesIndex) {
                                    chartContext.showSeries(name);
                                } else {
                                    chartContext.hideSeries(name);
                                }
                            });
                        }
                        return false; 
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
         noData: {
                text: 'Cargando datos...', 
                align: 'center',        
                verticalAlign: 'middle',
                style: {
                    color: '#64748b',
                    fontSize: '16px',
                    fontFamily: globalFont
                }
            },

            series: [],
            xaxis: { categories: [], labels: { style: { colors: '#64748b', fontWeight: 500 } } },
            grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
            // dibujar la leyenda con iconos para las visitas
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                useHTML: true,
                width: undefined,
                markers: { width: 0, height: 0, display: 'none' }, 
                formatter: function(seriesName, opts) {
                    const color = opts.w.config.colors[opts.seriesIndex];
                    const data = opts.w.config.series[opts.seriesIndex].data;
                    const total = data.reduce((a, b) => a + (Number(b) || 0), 0);
                    
                    const icon = seriesName.toLowerCase().includes('asignada') 
                        ? 'fas fa-calendar-alt' 
                        : 'fas fa-check-double';
                    
                    return `
                        <div style="display: flex; align-items: center; background: white; padding: 4px 12px; border-radius: 20px; border: 1px solid ${color}44; box-shadow: 0 2px 4px rgba(0,0,0,0.02); margin-left: 8px;">
                            <i class="${icon}" style="color: ${color}; margin-right: 8px; font-size: 14px;"></i>
                            <span style="color: #475569; font-weight: 600; font-size: 13px; margin-right: 5px;">${seriesName}:</span>
                            <span style="color: #1e293b; font-weight: 800; font-size: 14px;">${total}</span>
                        </div>`;
                }
            },
        };
        const chartZonas = new ApexCharts(document.querySelector("#chartZonas"), optionsZonas);
        chartZonas.render();

        // confia en livewire para actualizar datos de graficas

        let currentIcons = [];
        window.addEventListener('updateChart', event => {
            currentIcons = event.detail.icons;

            chartEstados.updateOptions({ series: event.detail.series, labels: event.detail.labels, colors: event.detail.colors });
        });

       window.addEventListener('updateChartZonas', event => {

        
    // verificar 0 en grafica
    const totalVisitas = event.detail.series.reduce((acc, serie) => {
        return acc + serie.data.reduce((a, b) => a + (Number(b) || 0), 0);
    }, 0);


    const tieneDatos = totalVisitas > 0;

    if (!tieneDatos) {
        chartZonas.updateOptions({
            series: [],
            xaxis: { categories: [] },
            noData: {
                text: 'No hay visitas de campo asignadas o realizadas',
                align: 'center',
                verticalAlign: 'middle',
                style: {
                    color: '#94a3b8', 
                    fontSize: '16px',
                    fontFamily: 'Inter, sans-serif',
                    fontWeight: 500
                }
            },
            responsive: [
                {
                    breakpoint: 1024,
                    options: {
                        noData: {
                            style: { fontSize: '14px' }
                        }
                    }
                }, 
                {
                    breakpoint: 640,
                    options: {
                        noData: {
                            style: { fontSize: '12px' }
                        }
                    }
                }
            ]
        });
    } else {
        // Si hay datos, actualizamos normalmente
        chartZonas.updateOptions({ 
            series: event.detail.series, 
            xaxis: { categories: event.detail.labels },
            noData: { text: 'Cargando datos...' }
        });
    }
});
    });
</script>
@endpush

</x-interno-layout>