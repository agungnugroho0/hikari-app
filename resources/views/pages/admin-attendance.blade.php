<div class="space-y-6">
    <div class="border-b border-neutral-200 pb-5">
        <h1 class="mt-1 text-2xl font-semibold text-neutral-950">Absensi Siswa</h1>
    </div>

    @if ($message)
        <p role="status" class="border-l-4 border-emerald-700 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ $message }}</p>
    @endif

    <div class="">
        <section class="space-y-5 border-b border-neutral-200 pb-6 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-8">
            <label class="block text-sm font-medium text-neutral-700">
                Cari siswa
                <div wire:ignore class="mt-1">
                    <select id="student-select" data-selected-nis="{{ $selectedNis }}" class="w-full">
                        <option value="">Pilih NIS atau nama siswa</option>
                        @foreach ($students as $student)
                            <option value="{{ $student['nis'] }}" @selected($selectedNis === $student['nis'])>
                                {{ $student['nis'] }} - {{ $student['name'] }} ({{ $student['class'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('selectedNis') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
            </label>
            <div class="flex gap-2">
                <label class="block max-w-xs text-sm font-medium text-neutral-700">
                    Tanggal absensi
                    <input type="date" wire:model.live="attendanceDate" class="mt-1 block min-h-11 w-full rounded-md border border-neutral-300 bg-white px-3 text-sm focus:border-red-800 focus:outline-none focus:ring-2 focus:ring-red-800/20">
                    @error('attendanceDate') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                </label>

                @if ($currentAttendance)
                    <p class="text-sm text-neutral-700">Status tercatat: <span class="font-semibold uppercase text-red-900">{{ $statusLabels[$currentAttendance->ket] ?? $currentAttendance->ket }}</span>. Pilih status lain untuk memperbarui.</p>
                @endif

                <div>
                    <p class="mb-2 text-sm font-medium text-neutral-700">Pilih status</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ([
                            ['key' => 'hadir', 'label' => 'Hadir', 'class' => 'border-emerald-700 bg-emerald-700 text-white hover:bg-emerald-800'],
                            ['key' => 'izin', 'label' => 'Izin', 'class' => 'border-amber-600 bg-amber-600 text-white hover:bg-amber-700'],
                            ['key' => 'alfa', 'label' => 'Alfa', 'class' => 'border-red-800 bg-red-800 text-white hover:bg-red-900'],
                            ['key' => 'sakit', 'label' => 'Sakit', 'class' => 'border-sky-700 bg-sky-700 text-white hover:bg-sky-800'],
                            ['key' => 'mensetsu', 'label' => 'Mensetsu', 'class' => 'border-neutral-700 bg-neutral-700 text-white hover:bg-neutral-800'],
                        ] as $status)
                            <button type="button" wire:click="saveAttendance('{{ $status['key'] }}')" wire:loading.attr="disabled" class="min-h-10 rounded-md border px-4 text-sm font-semibold transition disabled:opacity-60 {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- <aside class="space-y-3">
            <h2 class="text-sm font-semibold uppercase text-neutral-700">Kode laporan</h2>
            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                <dt class="text-neutral-600">Hadir</dt><dd class="font-semibold text-neutral-900">H</dd>
                <dt class="text-neutral-600">Izin</dt><dd class="font-semibold text-neutral-900">I</dd>
                <dt class="text-neutral-600">Alfa</dt><dd class="font-semibold text-neutral-900">A</dd>
                <dt class="text-neutral-600">Sakit</dt><dd class="font-semibold text-neutral-900">S</dd>
                <dt class="text-neutral-600">Mensetsu</dt><dd class="font-semibold text-neutral-900">M</dd>
            </dl>
        </aside> --}}
    </div>
</div>