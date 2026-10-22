<div>
    <div x-data="{ show: false, msg: '' }" x-on:schedule-notice.window="msg = $event.detail.message; show = true; setTimeout(() => show = false, 3000)" class="fixed top-5 right-5 z-50">
        <div x-show="show" x-transition class="bg-green-700 font-bold text-white px-4 py-3 shadow-lg"><span x-text="msg"></span></div>
    </div>

    @if ($showForm)
        <x-kembali wire:click="$set('showForm', false)" />
        <h1 class="text-2xl font-bold text-neutral-900 mb-4">{{ $scheduleId ? 'Edit Jadwal' : 'Tambah Jadwal' }}</h1>
        <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="flex flex-col md:col-span-2">
                <label for="schedule-title" class="text-xs text-gray-600 py-1 mt-2">Judul Pertemuan</label>
                <input id="schedule-title" wire:model="title" type="text" class="border-gray-500 focus:ring-red-800 rounded">
                @error('title') <span class="text-orange-600">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col">
                <label for="schedule-date" class="text-xs text-gray-600 py-1 mt-2">Tanggal dan Waktu</label>
                <input id="schedule-date" wire:model="date" type="datetime-local" class="border-gray-500 focus:ring-red-800 rounded">
                @error('date') <span class="text-orange-600">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col">
                <label for="schedule-method" class="text-xs text-gray-600 py-1 mt-2">Metode Seleksi</label>
                <select id="schedule-method" wire:model="method" class="border-gray-500 focus:ring-red-800 rounded">
                    <option value="">Pilih Metode Seleksi</option>
                    <option value="online">Online</option>
                    <option value="offline">Offline</option>
                </select>
                @error('method') <span class="text-orange-600">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col md:col-span-2">
                <label for="schedule-note" class="text-xs text-gray-600 py-1 mt-2">Note (opsional)</label>
                <textarea id="schedule-note" wire:model="note" rows="4" class="border-gray-500 focus:ring-red-800 rounded"></textarea>
                @error('note') <span class="text-orange-600">{{ $message }}</span> @enderror
            </div>
            <div class="flex gap-3 md:col-span-2">
                <button type="submit" class="bg-gray-100 px-4 py-2 font-bold hover:bg-gray-300 transition cursor-pointer">Simpan Jadwal</button>
                <button type="button" wire:click="$set('showForm', false)" class="bg-gray-100 px-4 py-2 hover:bg-gray-300 transition cursor-pointer">Batal</button>
            </div>
        </form>
    @else
    <h1 class="text-2xl font-bold text-neutral-900 mb-4">Schedule</h1>
    <div class="mb-4">
        <button wire:click="create" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-300 cursor-pointer">+ Jadwal</button>
    </div>

    <div class="overflow-x-auto rounded shadow">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr><th class="p-3">Judul Pertemuan</th><th class="p-3">Tanggal dan Waktu</th><th class="p-3">Metode</th><th class="p-3">Note</th><th class="p-3">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse ($schedules as $schedule)
                    <tr wire:key="schedule-{{ $schedule->id_jadwal }}" class="border-t hover:bg-gray-50">
                        <td class="p-3 font-medium">{{ $schedule->title }}</td>
                        <td class="p-3">{{ $schedule->date->locale('id')->translatedFormat('d M Y, H:i') }}</td>
                        <td class="p-3"><span class="rounded-full px-2 py-1 text-xs {{ $schedule->method === 'online' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">{{ $schedule->method === 'online' ? 'online' : 'offline' }}</span></td>
                        <td class="p-3 whitespace-pre-line">{{ $schedule->note ?: '-' }}</td>
                        <td class="p-3 whitespace-nowrap"><button wire:click="edit({{ $schedule->id_jadwal }})" class="text-blue-700 hover:underline cursor-pointer">Edit</button><button wire:click="confirmDelete({{ $schedule->id_jadwal }})" class="ml-3 text-red-700 hover:underline cursor-pointer">Hapus</button></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-gray-500">Belum ada jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

    @if ($showConfirm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="w-80 rounded-lg bg-white p-6 text-center shadow-xl"><h2 class="mb-3 text-lg font-bold text-red-600">Hapus Jadwal?</h2><p class="mb-5 text-sm text-gray-600">Tindakan ini tidak bisa dibatalkan.</p><div class="flex justify-center gap-3"><button wire:click="$set('showConfirm', false)" class="rounded bg-gray-200 px-4 py-2 hover:bg-gray-300 cursor-pointer">Batal</button><button wire:click="delete" class="rounded bg-red-600 px-4 py-2 text-white hover:bg-red-700 cursor-pointer">Ya, Hapus</button></div></div>
        </div>
    @endif
</div>
