<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
        Distribusi Jenis Kendaraan
    </h3>

    <div id="usageTypeChart"></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const options = {
        chart: {
            type: 'donut',
            height: 280,
            width: '100%',
            fontFamily: 'Inter, sans-serif'
        },
        series: @json($typeData['series']),
        labels: @json($typeData['labels']),
        colors: ['#3C50E0', '#80CAEE', '#10B981', '#374151', '#F59E0B'],
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '12px',
            markers: {
                radius: 99
            }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '16px',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                            }
                        },
                        value: {
                            fontSize: '16px'
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 250
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const chart = new ApexCharts(document.querySelector("#usageTypeChart"), options);
    chart.render();
});
</script>