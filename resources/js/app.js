import "./bootstrap";
import "flowbite";

window.ensureChartJs = (() => {
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
                existingScript.addEventListener("load", () => resolve(window.Chart), { once: true });
                existingScript.addEventListener("error", reject, { once: true });
                return;
            }

            const script = document.createElement("script");
            script.src = "https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js";
            script.async = true;
            script.dataset.chartjsLoader = "true";
            script.onload = () => resolve(window.Chart);
            script.onerror = reject;
            document.head.appendChild(script);
        });

        return chartLoader;
    };
})();

window.reportCharts = function reportCharts(config) {
    return {
        totalSeries: config.totalSeries,
        classSeries: config.classSeries,
        year: config.year,
        className: config.className,
        monthName: config.monthName,
        totalChart: null,
        classChart: null,
        async init() {
            await window.ensureChartJs();
            this.renderCharts();
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
                    mode: "index",
                },
                animation: {
                    duration: 900,
                    easing: "easeOutQuart",
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
                        backgroundColor: "#ffffff",
                    },
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: "#52525b",
                        },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: "#52525b",
                        },
                        grid: {
                            color: "rgba(113, 113, 122, 0.15)",
                        },
                    },
                },
            };
        },
        buildTotalConfig() {
            return {
                type: "line",
                data: {
                    labels: this.totalSeries.map((item) => item.month_name),
                    datasets: [{
                        label: `Lulusan ${this.year}`,
                        data: this.totalSeries.map((item) => item.total),
                        borderColor: "#7f1d1d",
                        backgroundColor: "rgba(220, 38, 38, 0.16)",
                        pointBorderColor: "#7f1d1d",
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
                type: "line",
                data: {
                    labels: this.classSeries.map((item) => item.label),
                    datasets: [{
                        label: `${this.className} - ${this.monthName}`,
                        data: this.classSeries.map((item) => item.total),
                        borderColor: "#b45309",
                        backgroundColor: "rgba(245, 158, 11, 0.18)",
                        pointBorderColor: "#b45309",
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
                                color: "#52525b",
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 8,
                            },
                        },
                    },
                },
            };
        },
    };
};
