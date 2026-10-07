import "./bootstrap";
import "./home";
import "flowbite";
import $ from "jquery";
import select2 from "select2";
import Toastify from "toastify-js";
// import { Html5Qrcode } from "html5-qrcode";
import QrScanner from "qr-scanner";

window.jQuery = window.$ = $;
select2(window, $);
// window.Html5Qrcode = Html5Qrcode;
window.Toastify = Toastify;

let qrScanner = null;
let qrScannerVideo = null;
let scannerRestartTimer = null;
let isScanning = false;
let toastListenerBound = false;
let reportListenerBound = false;
const rotatingPlaceholderTimers = new Map();

function initScanner() {
    const startBtn = document.getElementById("start-btn");
    const stopBtn = document.getElementById("stop-btn");
    const video = document.querySelector("#reader video");

    if (!startBtn || !stopBtn || !video) return;

    if (qrScanner && qrScannerVideo !== video) {
        clearTimeout(scannerRestartTimer);
        qrScanner.destroy();
        qrScanner = null;
        qrScannerVideo = null;
        isScanning = false;
    }

    const stopScanner = () => {
        clearTimeout(scannerRestartTimer);
        scannerRestartTimer = null;

        if (!qrScanner || !isScanning) return;

        qrScanner.stop();
        isScanning = false;
    };

    const startScanner = () => {
        if (isScanning) return;

        qrScanner ??= new QrScanner(
            video,
            (result) => {
                if (!isScanning || !window.Livewire) return;

                window.Livewire.dispatch("qr-scanned", { value: result.data });
                stopScanner();
                scannerRestartTimer = setTimeout(() => {
                    scannerRestartTimer = null;
                    startScanner();
                }, 1000);
            },
            { preferredCamera: "environment", maxScansPerSecond: 10 }
        );
        qrScannerVideo = video;

        isScanning = true;
        qrScanner.start().catch((error) => {
            isScanning = false;
            console.error("QR scanner failed to start", error);
        });
    };

    startBtn.onclick = startScanner;
    stopBtn.onclick = stopScanner;
    startScanner();
}

window.showToast = (message, type = "success") => {
    const config = {
        success: { color: "#16a34a", icon: "v" },
        error: { color: "#dc2626", icon: "x" },
        warning: { color: "#eab308", icon: "!" },
    };

    const toastType = config[type] || config.success;

    Toastify({
        text: `
            <div style="display:flex;align-items:center;gap:10px;">
                <span style="font-size:18px;">${toastType.icon}</span>
                <span>${message}</span>
            </div>
            <div class="toast-progress"></div>
        `,
        duration: 3000,
        gravity: "top",
        position: "right",
        escapeMarkup: false,
        stopOnFocus: true,
        style: {
            background: "#ffffff",
            borderRadius: "12px",
            padding: "12px 16px",
            boxShadow: "0 10px 25px rgba(0,0,0,0.2)",
            borderLeft: `4px solid ${toastType.color}`,
        },
        className: "custom-toast",
    }).showToast();
};

function bindToastCommand() {
    if (toastListenerBound || !window.Livewire) return;

    window.Livewire.on("notif", (event) => {
        window.showToast(event.message, event.type);
    });

    toastListenerBound = true;
}

window.ensureChartJs = (() => {
    let chartLoader = null;

    return () => {
        if (window.Chart) return Promise.resolve(window.Chart);
        if (chartLoader) return chartLoader;

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
        attendanceSeries: config.attendanceSeries,
        attendanceRecap: config.attendanceRecap,
        year: config.year,
        className: config.className,
        monthName: config.monthName,
        attendanceClassName: config.attendanceClassName,
        attendanceMonthName: config.attendanceMonthName,
        baseOptions() {
            return {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                animation: { duration: 900, easing: "easeOutQuart" },
                plugins: { legend: { display: false } },
                elements: {
                    line: { tension: 0.35, borderWidth: 3 },
                    point: { radius: 4, hoverRadius: 6, borderWidth: 2, backgroundColor: "#ffffff" },
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: "#52525b" } },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: "#52525b" },
                        grid: { color: "rgba(113, 113, 122, 0.15)" },
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
                options: this.baseOptions(),
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
                            ticks: { color: "#52525b", maxRotation: 0, autoSkip: true, maxTicksLimit: 8 },
                        },
                    },
                },
            };
        },
        buildAttendanceConfig() {
            return {
                type: "line",
                data: {
                    labels: this.attendanceSeries.map((item) => item.label),
                    datasets: [
                        {
                            label: "Hadir",
                            data: this.attendanceSeries.map((item) => item.hadir),
                            borderColor: "#166534",
                            backgroundColor: "rgba(34, 197, 94, 0.18)",
                            pointBorderColor: "#166534",
                            fill: false,
                        },
                        {
                            label: "Mensetsu",
                            data: this.attendanceSeries.map((item) => item.mensetsu),
                            borderColor: "#1d4ed8",
                            backgroundColor: "rgba(59, 130, 246, 0.18)",
                            pointBorderColor: "#1d4ed8",
                            fill: false,
                        },
                        {
                            label: "Ijin",
                            data: this.attendanceSeries.map((item) => item.ijin),
                            borderColor: "#b45309",
                            backgroundColor: "rgba(245, 158, 11, 0.18)",
                            pointBorderColor: "#b45309",
                            fill: false,
                        },
                        {
                            label: "Sakit",
                            data: this.attendanceSeries.map((item) => item.sakit),
                            borderColor: "#a21caf",
                            backgroundColor: "rgba(217, 70, 239, 0.18)",
                            pointBorderColor: "#a21caf",
                            fill: false,
                        },
                        {
                            label: "Alfa",
                            data: this.attendanceSeries.map((item) => item.alfa),
                            borderColor: "#991b1b",
                            backgroundColor: "rgba(239, 68, 68, 0.18)",
                            pointBorderColor: "#991b1b",
                            fill: false,
                        },
                    ],
                },
                options: {
                    ...this.baseOptions(),
                    plugins: {
                        ...this.baseOptions().plugins,
                        legend: { display: true, position: "bottom" },
                    },
                    scales: {
                        ...this.baseOptions().scales,
                        x: {
                            ...this.baseOptions().scales.x,
                            ticks: { color: "#52525b", maxRotation: 0, autoSkip: true, maxTicksLimit: 10 },
                        },
                    },
                },
            };
        },
        buildAttendanceRecapConfig() {
            return {
                type: "bar",
                data: {
                    labels: this.attendanceRecap.map((item) => item.label),
                    datasets: [{
                        label: `${this.attendanceClassName} - ${this.attendanceMonthName}`,
                        data: this.attendanceRecap.map((item) => item.percentage),
                        backgroundColor: this.attendanceRecap.map((item) => item.color),
                        borderRadius: 8,
                    }],
                },
                options: {
                    ...this.baseOptions(),
                    plugins: {
                        ...this.baseOptions().plugins,
                        legend: { display: false },
                        tooltip: { callbacks: { label: (context) => `${Number(context.raw).toFixed(1)}%` } },
                    },
                    scales: {
                        ...this.baseOptions().scales,
                        y: {
                            ...this.baseOptions().scales.y,
                            max: 100,
                            ticks: { color: "#52525b", callback: (value) => `${value}%` },
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
            console.error("Chart.js failed to load", error);
            return;
        }

        const totalCanvas = document.getElementById("total-graduation-chart");
        const classCanvas = document.getElementById("class-graduation-chart");
        const attendanceCanvas = document.getElementById("class-attendance-chart");
        const attendanceRecapCanvas = document.getElementById("attendance-recap-chart");
        const chartFactory = window.reportCharts(payload);

        if (totalCanvas) {
            if (this.totalChart) this.totalChart.destroy();
            this.totalChart = new window.Chart(totalCanvas, chartFactory.buildTotalConfig());
        }

        if (classCanvas) {
            if (this.classChart) this.classChart.destroy();
            this.classChart = new window.Chart(classCanvas, chartFactory.buildClassConfig());
        }

        if (attendanceCanvas) {
            if (this.attendanceChart) this.attendanceChart.destroy();
            this.attendanceChart = new window.Chart(attendanceCanvas, chartFactory.buildAttendanceConfig());
        }

        if (attendanceRecapCanvas) {
            if (this.attendanceRecapChart) this.attendanceRecapChart.destroy();
            this.attendanceRecapChart = new window.Chart(attendanceRecapCanvas, chartFactory.buildAttendanceRecapConfig());
        }
    },
};

function bindReportChartsCommand() {
    if (reportListenerBound) return;

    window.latestPayload = null;
    window.addEventListener("report-charts-data", (event) => {
        const payload = event.detail?.payload;
        if (!payload) return;

        window.latestPayload = payload;
        window.reportChartManager.render(payload);
    });

    reportListenerBound = true;
}

function cleanupRotatingPlaceholders() {
    for (const [input, state] of rotatingPlaceholderTimers.entries()) {
        if (document.contains(input)) {
            continue;
        }

        clearInterval(state.timer);
        rotatingPlaceholderTimers.delete(input);
    }
}

function initRotatingPlaceholders() {
    cleanupRotatingPlaceholders();

    document.querySelectorAll("[data-rotating-placeholder]").forEach((input) => {
        if (rotatingPlaceholderTimers.has(input)) {
            return;
        }

        let examples = [];
        try {
            examples = JSON.parse(input.dataset.rotatingPlaceholder || "[]");
        } catch (error) {
            console.error("Invalid rotating placeholder data", error);
            return;
        }

        if (!Array.isArray(examples) || examples.length === 0) {
            return;
        }

        let index = Math.max(0, examples.indexOf(input.getAttribute("placeholder")));
        const changePlaceholder = () => {
            if (!document.contains(input)) {
                const state = rotatingPlaceholderTimers.get(input);
                if (state) clearInterval(state.timer);
                rotatingPlaceholderTimers.delete(input);
                return;
            }

            index = (index + 1) % examples.length;
            input.classList.add("opacity-60");

            setTimeout(() => {
                if (!document.contains(input)) {
                    return;
                }

                input.setAttribute("placeholder", examples[index]);
                input.classList.remove("opacity-60");
            }, 150);
        };

        const timer = setInterval(changePlaceholder, 3500);
        rotatingPlaceholderTimers.set(input, { timer });
    });
}

window.ensureJquery = () => Promise.resolve(window.jQuery);
window.ensureSelect2 = () => Promise.resolve(window.jQuery.fn.select2);

window.initStudentSelect2 = async function initStudentSelect2() {
    const select = document.getElementById("student-select");

    if (!select) {
        return;
    }

    if (!window.Livewire) {
        return;
    }

    try {
        await window.ensureSelect2();
    } catch (error) {
        console.error("Select2 failed to load", error);
        return;
    }

    const $select = window.jQuery(select);
    const componentElement = select.closest('[wire\\:id]');
    const componentId = componentElement ? componentElement.getAttribute("wire:id") : null;
    const component = componentId ? window.Livewire.find(componentId) : null;

    if (!component) {
        return;
    }

    if ($select.hasClass("select2-hidden-accessible")) {
        $select.off(".student-attendance");
        $select.select2("destroy");
    }

    $select.select2({
        placeholder: "Cari siswa berdasarkan NIS atau nama",
        width: "100%",
    });

    $select.val(select.dataset.selectedNis || "").trigger("change.select2");
    $select.on("change.student-attendance", function () {
        component.set("selectedNis", this.value);
    });
};

function bootFrontendCommands() {
    initScanner();
    bindToastCommand();
    bindReportChartsCommand();
    window.initStudentSelect2();
    initRotatingPlaceholders();
}

document.addEventListener("DOMContentLoaded", bootFrontendCommands);
document.addEventListener("livewire:navigated", bootFrontendCommands);
document.addEventListener("livewire:navigating", () => {
    for (const state of rotatingPlaceholderTimers.values()) {
        clearInterval(state.timer);
    }

    rotatingPlaceholderTimers.clear();
});
document.addEventListener("livewire:init", () => {
    bindToastCommand();
    window.initStudentSelect2();
    initRotatingPlaceholders();
});
