<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>

<body>
    <div class="p-4 sm:mx-24 sm:flex">
        <x-nav-bar class=""></x-nav-bar>
        <div class="p-4 border-r-2 border-default w-full">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <script>
        window.ensureChartJs = window.ensureChartJs || (() => {
            let chartLoader = null;

            return () => {
                if (window.Chart) {
                    return Promise.resolve(window.Chart);
                }

                if (chartLoader) {
                    return chartLoader;
                }

                chartLoader = new Promise((resolve, reject) => {
                    const existingScript = document.querySelector('script[data-chartjs-loader="true"]');

                    if (existingScript) {
                        existingScript.addEventListener('load', () => resolve(window.Chart), { once: true });
                        existingScript.addEventListener('error', reject, { once: true });
                        return;
                    }

                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js';
                    script.async = true;
                    script.dataset.chartjsLoader = 'true';
                    script.onload = () => resolve(window.Chart);
                    script.onerror = reject;
                    document.head.appendChild(script);
                });

                return chartLoader;
            };
        })();

        window.reportCharts = function reportCharts(config) {
            console.log("VERSI BARU MASUK 🔥");
            return {
                totalSeries: config.totalSeries,
                classSeries: config.classSeries,
                attendanceSeries: config.attendanceSeries,
                attendanceRecap: config.attendanceRecap,
                year: config.year,
                className: config.className,
                monthName: config.monthName,
                attendanceClassName: config.attendanceClassName,
                attendanceMonthName: config.attendanceMonthName,
                totalChart: null,
                classChart: null,
                attendanceChart: null,
                attendanceRecapChart: null,
                async init() {
                    try {
                        await window.ensureChartJs();
                        this.renderCharts();
                    } catch (error) {
                        console.error('Chart.js failed to load', error);
                    }
                },
                renderCharts() {
                    if (!window.Chart || !this.$refs.totalCanvas || !this.$refs.classCanvas) {
                        return;
                    }

                    if (this.totalChart) {
                        this.totalChart.destroy();
                    }

                    if (this.classChart) {
                        this.classChart.destroy();
                    }

                    this.totalChart = new window.Chart(this.$refs.totalCanvas, this.buildTotalConfig());
                    this.classChart = new window.Chart(this.$refs.classCanvas, this.buildClassConfig());
                },
                baseOptions() {
                    return {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        animation: {
                            duration: 900,
                            easing: 'easeOutQuart',
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                        },
                        elements: {
                            line: {
                                tension: 0.35,
                                borderWidth: 3,
                            },
                            point: {
                                radius: 4,
                                hoverRadius: 6,
                                borderWidth: 2,
                                backgroundColor: '#ffffff',
                            },
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                },
                                ticks: {
                                    color: '#52525b',
                                },
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0,
                                    color: '#52525b',
                                },
                                grid: {
                                    color: 'rgba(113, 113, 122, 0.15)',
                                },
                            },
                        },
                    };
                },
                buildTotalConfig() {
                    return {
                        type: 'line',
                        data: {
                            labels: this.totalSeries.map((item) => item.month_name),
                            datasets: [{
                                label: `Lulusan ${this.year}`,
                                data: this.totalSeries.map((item) => item.total),
                                borderColor: '#7f1d1d',
                                backgroundColor: 'rgba(220, 38, 38, 0.16)',
                                pointBorderColor: '#7f1d1d',
                                fill: true,
                            }],
                        },
                        options: {
                            ...this.baseOptions(),
                        },
                    };
                },
                buildClassConfig() {
                    return {
                        type: 'line',
                        data: {
                            labels: this.classSeries.map((item) => item.label),
                            datasets: [{
                                label: `${this.className} - ${this.monthName}`,
                                data: this.classSeries.map((item) => item.total),
                                borderColor: '#b45309',
                                backgroundColor: 'rgba(245, 158, 11, 0.18)',
                                pointBorderColor: '#b45309',
                                fill: true,
                            }],
                        },
                        options: {
                            ...this.baseOptions(),
                            scales: {
                                ...this.baseOptions().scales,
                                x: {
                                    ...this.baseOptions().scales.x,
                                    ticks: {
                                        color: '#52525b',
                                        maxRotation: 0,
                                        autoSkip: true,
                                        maxTicksLimit: 8,
                                    },
                                },
                            },
                        },
                    };
                },
                buildAttendanceConfig() {
                    return {
                        type: 'line',
                        data: {
                            labels: this.attendanceSeries.map((item) => item.label),
                            datasets: [{
                                label: 'Hadir',
                                data: this.attendanceSeries.map((item) => item.hadir),
                                borderColor: '#166534',
                                backgroundColor: 'rgba(34, 197, 94, 0.18)',
                                pointBorderColor: '#166534',
                                fill: false,
                            }, {
                                label: 'Mensetsu',
                                data: this.attendanceSeries.map((item) => item.mensetsu),
                                borderColor: '#1d4ed8',
                                backgroundColor: 'rgba(59, 130, 246, 0.18)',
                                pointBorderColor: '#1d4ed8',
                                fill: false,
                            }, {
                                label: 'Ijin',
                                data: this.attendanceSeries.map((item) => item.ijin),
                                borderColor: '#b45309',
                                backgroundColor: 'rgba(245, 158, 11, 0.18)',
                                pointBorderColor: '#b45309',
                                fill: false,
                            }, {
                                label: 'Alfa',
                                data: this.attendanceSeries.map((item) => item.alfa),
                                borderColor: '#991b1b',
                                backgroundColor: 'rgba(239, 68, 68, 0.18)',
                                pointBorderColor: '#991b1b',
                                fill: false,
                            }],
                        },
                        options: {
                            ...this.baseOptions(),
                            plugins: {
                                ...this.baseOptions().plugins,
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                },
                            },
                            scales: {
                                ...this.baseOptions().scales,
                                x: {
                                    ...this.baseOptions().scales.x,
                                    ticks: {
                                        color: '#52525b',
                                        maxRotation: 0,
                                        autoSkip: true,
                                        maxTicksLimit: 10,
                                    },
                                },
                            },
                        },
                    };
                },
                buildAttendanceRecapConfig() {
                    return {
                        type: 'bar',
                        data: {
                            labels: this.attendanceRecap.map((item) => item.label),
                            datasets: [{
                                label: `${this.attendanceClassName} - ${this.attendanceMonthName}`,
                                data: this.attendanceRecap.map((item) => item.total),
                                backgroundColor: this.attendanceRecap.map((item) => item.color),
                                borderRadius: 8,
                            }],
                        },
                        options: {
                            ...this.baseOptions(),
                            plugins: {
                                ...this.baseOptions().plugins,
                                legend: {
                                    display: false,
                                },
                            },
                        },
                    };
                },
            };
        };

        window.reportChartManager = {
            totalChart: null,
            classChart: null,
            attendanceChart: null,
            attendanceRecapChart: null,
            async render(payload) {
                
                try {
                    await window.ensureChartJs();
                } catch (error) {
                    console.error('Chart.js failed to load', error);
                    return;
                }

                const totalCanvas = document.getElementById('total-graduation-chart');
                const classCanvas = document.getElementById('class-graduation-chart');
                const attendanceCanvas = document.getElementById('class-attendance-chart');
                const attendanceRecapCanvas = document.getElementById('attendance-recap-chart');

                const chartFactory = window.reportCharts(payload);

                if (totalCanvas) {
                    if (this.totalChart) this.totalChart.destroy();
                    this.totalChart = new Chart(totalCanvas, chartFactory.buildTotalConfig());
                }

                if (classCanvas) {
                    if (this.classChart) this.classChart.destroy();
                    this.classChart = new Chart(classCanvas, chartFactory.buildClassConfig());
                }

                if (attendanceCanvas) {
                    console.log(payload.attendanceSeries);
                    console.log(chartFactory);
                    if (this.attendanceChart) this.attendanceChart.destroy();
                    this.attendanceChart = new Chart(attendanceCanvas, chartFactory.buildAttendanceConfig());
                }

                if (attendanceRecapCanvas) {
                    if (this.attendanceRecapChart) this.attendanceRecapChart.destroy();
                    this.attendanceRecapChart = new Chart(attendanceRecapCanvas, chartFactory.buildAttendanceRecapConfig());
                }
            },
        };

        window.latestPayload = null;

        window.addEventListener('report-charts-data', (event) => {
            const payload = event.detail?.payload;

            if (!payload) return;

            window.latestPayload = payload;

            window.reportChartManager.render(payload);
        });
    </script>

</body>

</html>
