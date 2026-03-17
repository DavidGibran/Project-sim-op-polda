@props(['trendData'])

<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Trend Operasional Kendaraan
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Visualisasi aktivitas armada dalam 12 bulan terakhir
            </p>
        </div>
    </div>

    <div>
        <div id="fleetTrendChart" class="w-full h-[350px] overflow-hidden"></div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const trendOptions = {
            series: [{
                name: 'Kendaraan Aktif',
                data: @json($trendData['aktif'])
            }, {
                name: 'Dalam Penugasan',
                data: @json($trendData['penugasan'])
            }, {
                name: 'Dalam Perbaikan',
                data: @json($trendData['perbaikan'])
            }],
            chart: {
                fontFamily: 'Satoshi, sans-serif',
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#62331021',
                    top: 10,
                    left: 0,
                    blur: 4,
                    opacity: 0.1,
                },
                toolbar: {
                    show: false,
                },
            },
            colors: ['#10B981', '#3B82F6', '#EF4444'], // Hijau, Biru, Merah
            stroke: {
                width: [2, 2, 2],
                curve: 'smooth',
            },
            grid: {
                xaxis: {
                    lines: {
                        show: true,
                    },
                },
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
            },
            dataLabels: {
                enabled: false,
            },
            markers: {
                size: 4,
                colors: '#fff',
                strokeColors: ['#10B981', '#3B82F6', '#EF4444'],
                strokeWidth: 3,
                strokeOpacity: 0.9,
                strokeDashArray: 0,
                fillOpacity: 1,
                discrete: [],
                hover: {
                    size: undefined,
                    sizeOffset: 5,
                },
            },
            xaxis: {
                type: 'category',
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            yaxis: {
                title: {
                    style: {
                        fontSize: '0px',
                    },
                },
                min: 0,
                max: 30,
                tickAmount: 6,
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                fontFamily: 'Satoshi',
                fontWeight: 500,
                fontSize: '14px',
                markers: {
                    radius: 99,
                },
            },
        };

        const trendChart = new ApexCharts(document.querySelector("#fleetTrendChart"), trendOptions);
        trendChart.render();
    });
</script>
@endpush
