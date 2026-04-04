<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
        Tren Pemakaian
    </h3>

    <div id="usageTrendChart"></div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const options = {
        chart: {
            type: 'line',
            height: 280,
            width: '100%',
            toolbar: {
                show: false
            },
            fontFamily: 'Inter, sans-serif'
        },
        series: [{
            name: 'Total Penugasan',
            data: @json($trendData['series'])
        }],
        colors: ['#3C50E0'],
        xaxis: {
            categories: @json($trendData['categories']),
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        yaxis: {
            title: {
                style: {
                    fontSize: '0px'
                }
            }
        },
        grid: {
            strokeDashArray: 5,
            xaxis: {
                lines: {
                    show: true
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 4,
            colors: ['#3C50E0'],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: {
                size: 7
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + " Penugasan"
                }
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#usageTrendChart"), options);
    chart.render();
});
</script>