<div class="rounded-[10px] bg-white p-6 shadow-1 dark:bg-gray-dark dark:shadow-card h-full flex flex-col">
    
    <div class="mb-5">
        <h4 class="text-xl font-semibold text-dark dark:text-white">
            Status Perbaikan
        </h4>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Distribusi status perbaikan
        </p>
    </div>

    <div class="flex-1 flex items-center justify-center">
        <div id="repairStatusChart" class="w-full"></div>
    </div>

</div>

@once
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
@endonce

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const el = document.querySelector("#repairStatusChart");
    if (!el) return;

    let chart;

    function getChartOptions() {
        const isDark = document.documentElement.classList.contains("dark");

        return {
            series: @json($statusData['series']),
            labels: @json($statusData['labels']),
            chart: {
                type: 'donut',
                height: 260,
                fontFamily: 'Satoshi, sans-serif',
                background: 'transparent'
            },
            colors: ['#3C50E0', '#80CAEE', '#10B981', '#FFBA00', '#FB5454'],
            stroke: {
                show: true,
                width: 3,
                colors: [isDark ? '#24303F' : '#ffffff']
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontFamily: 'Satoshi, sans-serif',
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: [isDark ? '#E5E7EB' : '#1F2937']
                },
                dropShadow: {
                    enabled: false
                }
            },
            legend: {
                show: true,
                position: 'bottom',
                fontFamily: 'Satoshi, sans-serif',
                labels: {
                    colors: isDark ? '#DEE4EE' : '#637381'
                }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%'
                    }
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            }
        };
    }

    function renderChart() {
        if (chart) {
            chart.destroy();
        }

        chart = new ApexCharts(el, getChartOptions());
        chart.render();
    }

    renderChart();

    const observer = new MutationObserver(() => {
        renderChart();
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>
@endpush