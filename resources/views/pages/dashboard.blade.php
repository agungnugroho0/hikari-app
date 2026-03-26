<div wire:key="dashboard-{{ $year }}" class="space-y-6">
    <x-loading wire:loading wire:target="year"></x-loading>

    <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Dashboard</h1>
        </div>

        <label class="flex w-full max-w-xs flex-col gap-1 text-sm">
            <span class="font-medium text-neutral-700">Tahun rekapan</span>
            <select wire:model.live="year" class="rounded border border-neutral-300 px-3 py-2 text-sm">
                @foreach ($this->availableYears as $availableYear)
                    <option value="{{ $availableYear }}">{{ $availableYear }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <section class="grid gap-4 md:grid-cols-3">
        <div class="rounded-lg border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Tahun</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">{{ $year }}</p>
        </div>
        <div class="rounded-lg border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Total kelulusan</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">{{ $this->yearlyGraduationChart['year_total'] }}</p>
        </div>
        <div class="rounded-lg border border-neutral-200 bg-white p-4 shadow-sm">
            <p class="text-sm text-neutral-500">Job order terjadwal</p>
            <p class="mt-2 text-3xl font-bold text-neutral-900">{{ count($this->jobOrderEvents) }}</p>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)]">
        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Grafik Rekapan Tahunan</h2>
                    <p class="text-sm text-neutral-500">Jumlah lulusan per bulan selama {{ $year }}.</p>
                </div>
                <div class="text-sm text-neutral-600">
                    <p>Bulan aktif: <span class="font-semibold text-neutral-900">{{ $this->yearlyGraduationChart['active_months'] }}</span></p>
                    <p>Puncak: <span class="font-semibold text-neutral-900">{{ $this->yearlyGraduationChart['peak_month'] }}</span></p>
                </div>
            </div>

            <div class="h-80" wire:ignore>
                <canvas id="dashboard-yearly-chart"></canvas>
            </div>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm">
            <div class="rounded-lg border-neutral-200 bg-neutral-50 p-3" wire:ignore>
                <div id="job-order-calendar"></div>
            </div>
        </article>
    </section>

    <script type="application/json" id="dashboard-initial-payload">@json($this->dashboardPayload)</script>

    <script>
        window.ensureFullCalendar = window.ensureFullCalendar || (() => {
            let fullCalendarLoader = null;

            return () => {
                if (window.FullCalendar) {
                    return Promise.resolve(window.FullCalendar);
                }

                if (fullCalendarLoader) {
                    return fullCalendarLoader;
                }

                fullCalendarLoader = new Promise((resolve, reject) => {
                    const existingScript = document.querySelector('script[data-fullcalendar-loader="true"]');

                    if (existingScript) {
                        existingScript.addEventListener('load', () => resolve(window.FullCalendar), { once: true });
                        existingScript.addEventListener('error', reject, { once: true });
                        return;
                    }

                    const script = document.createElement('script');
                    script.src = 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.20/index.global.min.js';
                    script.async = true;
                    script.dataset.fullcalendarLoader = 'true';
                    script.onload = () => resolve(window.FullCalendar);
                    script.onerror = reject;
                    document.head.appendChild(script);
                });

                return fullCalendarLoader;
            };
        })();

        window.dashboardYearlyChartManager = window.dashboardYearlyChartManager || {
            chart: null,
            async render(payload) {
                try {
                    await window.ensureChartJs();
                } catch (error) {
                    console.error('Chart.js failed to load', error);
                    return;
                }

                const canvas = document.getElementById('dashboard-yearly-chart');

                if (!canvas) {
                    return;
                }

                if (this.chart) {
                    this.chart.destroy();
                }

                this.chart = new window.Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: payload.series.map((item) => item.month_name),
                        datasets: [{
                            label: `Lulusan ${payload.year}`,
                            data: payload.series.map((item) => item.total),
                            borderColor: '#7f1d1d',
                            backgroundColor: 'rgba(220, 38, 38, 0.16)',
                            pointBorderColor: '#7f1d1d',
                            pointBackgroundColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            borderWidth: 3,
                            tension: 0.35,
                            fill: true,
                        }],
                    },
                    options: {
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
                    },
                });
            },
        };

        window.dashboardJobOrderCalendarManager = window.dashboardJobOrderCalendarManager || {
            calendar: null,
            async render(payload) {
                try {
                    await window.ensureFullCalendar();
                } catch (error) {
                    console.error('FullCalendar failed to load', error);
                    return;
                }

                const element = document.getElementById('job-order-calendar');

                if (!element || !window.FullCalendar) {
                    return;
                }

                if (this.calendar) {
                    this.calendar.destroy();
                }

                const events = payload.jobOrderEvents.map((event) => ({
                    title: event.title,
                    start: event.sort_key,
                    allDay: true,
                    extendedProps: {
                        company: event.company,
                        soName: event.so_name,
                    },
                }));

                this.calendar = new window.FullCalendar.Calendar(element, {
                    initialView: 'dayGridMonth',
                    locale: 'id',
                    height: 'auto',
                    fixedWeekCount: false,
                    showNonCurrentDates: true,
                    dayMaxEventRows: 3,
                    contentHeight: 560,
                    headerToolbar: {
                        left: 'today prev,next',
                        center: 'title',
                        right: '',
                    },
                    buttonText: {
                        today: 'Hari ini',
                    },
                    events,
                    eventDisplay: 'block',
                    eventBackgroundColor: '#0f766e',
                    eventBorderColor: '#0f766e',
                    eventTextColor: '#ffffff',
                    dayHeaderFormat: {
                        weekday: 'short',
                    },
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        meridiem: false,
                    },
                    eventContent(arg) {
                        return {
                            html: `<div class="truncate px-1 text-xs font-medium">${arg.event.title}</div>`,
                        };
                    },
                    eventDidMount(info) {
                        const company = info.event.extendedProps.company || '';
                        const soName = info.event.extendedProps.soName || '';
                        info.el.title = [info.event.title, company, soName].filter(Boolean).join(' | ');
                    },
                });

                this.calendar.render();
            },
        };

        if (!window.__dashboardYearlyChartListenerRegistered) {
            window.addEventListener('dashboard-yearly-chart-data', (event) => {
                const payload = event.detail?.payload;

                if (!payload) {
                    return;
                }

                requestAnimationFrame(() => {
                    window.dashboardYearlyChartManager.render(payload);
                    window.dashboardJobOrderCalendarManager.render(payload);
                });
            });

            window.__dashboardYearlyChartListenerRegistered = true;
        }

        const dashboardInitialPayloadElement = document.getElementById('dashboard-initial-payload');

        if (dashboardInitialPayloadElement) {
            try {
                const initialPayload = JSON.parse(dashboardInitialPayloadElement.textContent);

                requestAnimationFrame(() => {
                    window.dashboardYearlyChartManager.render(initialPayload);
                    window.dashboardJobOrderCalendarManager.render(initialPayload);
                });
            } catch (error) {
                console.error('Failed to parse initial dashboard payload', error);
            }
        }
    </script>
</div>
