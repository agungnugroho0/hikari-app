window.initSenseiStudentSelect2 = async function initSenseiStudentSelect2() {
    const select = document.getElementById("sensei-student-select");

    if (!select || !window.Livewire) {
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
        $select.off(".sensei-student-select");
        $select.select2("destroy");
    }

    $select.select2({
        placeholder: "Cari siswa berdasarkan NIS atau nama",
        width: "100%",
    });

    $select.val(select.dataset.selectedNis || "").trigger("change.select2");
    $select.on("change.sensei-student-select", function () {
        component.set("selectedNis", this.value);
    });
};

window.senseiDashboardChartManager = window.senseiDashboardChartManager || {
    chart: null,
    async render(payload) {
        if (!payload) {
            return;
        }

        try {
            await window.ensureChartJs();
        } catch (error) {
            console.error("Chart.js failed to load", error);
            return;
        }

        const canvas = document.getElementById("sensei-graduation-chart");

        if (!canvas) {
            return;
        }

        if (this.chart) {
            this.chart.destroy();
        }

        this.chart = new window.Chart(canvas, {
            type: "line",
            data: {
                labels: payload.series.map((item) => item.month_name),
                datasets: [{
                    label: `Kelulusan ${payload.className} ${payload.year}`,
                    data: payload.series.map((item) => item.total),
                    borderColor: "#7f1d1d",
                    backgroundColor: "rgba(220, 38, 38, 0.12)",
                    pointBorderColor: "#7f1d1d",
                    pointBackgroundColor: "#ffffff",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 5,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: "#52525b" },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: "#52525b" },
                        grid: { color: "rgba(113, 113, 122, 0.12)" },
                    },
                },
            },
        });
    },
};

function renderInitialSenseiHomeChart() {
    const payloadElement = document.getElementById("sensei-home-chart-payload");

    if (!payloadElement) {
        return;
    }

    try {
        window.senseiDashboardChartManager.render(JSON.parse(payloadElement.textContent));
    } catch (error) {
        console.error("Failed to parse initial sensei dashboard payload", error);
    }
}

function bootSenseiHome() {
    window.initSenseiStudentSelect2();
    renderInitialSenseiHomeChart();
}

if (!window.__senseiDashboardChartListenerRegistered) {
    window.addEventListener("sensei-dashboard-chart-data", (event) => {
        window.senseiDashboardChartManager.render(event.detail?.payload);
    });

    window.__senseiDashboardChartListenerRegistered = true;
}

if (!window.__senseiStudentSelect2ListenersRegistered) {
    document.addEventListener("DOMContentLoaded", bootSenseiHome);
    document.addEventListener("livewire:navigated", bootSenseiHome);
    document.addEventListener("livewire:init", () => {
        window.Livewire.hook("morph.updated", ({ el }) => {
            if (el.querySelector && el.querySelector("#sensei-student-select")) {
                window.initSenseiStudentSelect2();
                renderInitialSenseiHomeChart();
            }
        });
    });

    window.__senseiStudentSelect2ListenersRegistered = true;
}
