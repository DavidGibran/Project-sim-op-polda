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
            height: 300,
        },
        series: [{
            name: 'Pemakaian',
            data: @json($trendData['series'])
        }],
        xaxis: {
            categories: @json($trendData['categories'])
        },
        stroke: {
            curve: 'smooth'
        }
    };

    const chart = new ApexCharts(document.querySelector("#usageTrendChart"), options);
    chart.render();
});
</script>