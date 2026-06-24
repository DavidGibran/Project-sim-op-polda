<div class="rounded-[10px] bg-white p-6 shadow-1 dark:bg-gray-dark dark:shadow-card">
    <div class="mb-5">
        <h4 class="text-xl font-semibold text-dark dark:text-white">
            Tren Kerusakan
        </h4>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Jumlah laporan kerusakan per bulan
        </p>
    </div>

    <div id="damageTrendChart" class="-ml-3"></div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const el = document.querySelector("#damageTrendChart");
    if (!el) return;

    let chart;

    function getChartOptions() {
        const isDark = document.documentElement.classList.contains("dark");

        return {
            series: [{
                name: 'Kerusakan',
                data: @json($trendData['series'])
            }],
            chart: {
                type: 'area',
                height: 260,
                toolbar: {
                    show: false
                },
                fontFamily: 'Satoshi, sans-serif',
                background: 'transparent'
            },
            colors: ['#FB5454'], // Red color for damage
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.3,
                    opacityTo: 0.02,
                    stops: [0, 100]
                }
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: isDark ? '#2E3A47' : '#E2E8F0',
                strokeDashArray: 5,
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            xaxis: {
                categories: @json($trendData['categories']),
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: isDark ? '#AEB7C0' : '#64748B',
                        fontFamily: 'Satoshi, sans-serif'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: isDark ? '#AEB7C0' : '#64748B',
                        fontFamily: 'Satoshi, sans-serif'
                    }
                }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light'
            },
            legend: {
                show: false
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

    // Use ResizeObserver for layout changes
    const resizeObserver = new ResizeObserver(() => {
        if (chart) {
            chart.windowResizeHandler();
        }
    });
    resizeObserver.observe(el.parentElement);
});
</script>
@endpush
