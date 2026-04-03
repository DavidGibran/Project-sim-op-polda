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
            height: 300,
        },
        series: @json($typeData['series']),
        labels: @json($typeData['labels']),
        legend: {
            position: 'bottom'
        }
    };

    const chart = new ApexCharts(document.querySelector("#usageTypeChart"), options);
    chart.render();
});
</script>