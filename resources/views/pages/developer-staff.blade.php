<div class="space-y-6">
    <div class="flex flex-wrap items-end justify-between gap-4 border-b border-neutral-200 pb-5">
        <div>
            <p class="text-sm font-medium text-red-800">Developer Console</p>
            <h1 class="mt-1 text-2xl font-semibold text-neutral-950">Pengelolaan Staf</h1>
        </div>
        @if ($mode === 'list')
            <button type="button" wire:click="create" class="inline-flex min-h-10 items-center rounded-md bg-red-900 px-4 text-sm font-semibold text-white transition hover:bg-red-800">
                Tambah staf
            </button>
        @endif
    </div>

    @if ($message)
        <p role="status" class="border-l-4 border-emerald-700 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">{{ $message }}</p>
    @endif

    @if ($mode !== 'list')
        <section class="max-w-3xl border-b border-neutral-200 pb-6">
            <h2 class="text-lg font-semibold text-neutral-900">{{ $mode === 'edit' ? 'Ubah data staf' : 'Tambah staf' }}</h2>
            <form wire:submit="save" class="mt-4 grid gap-4 sm:grid-cols-2">
                <label class="text-sm font-medium text-neutral-700">
                    Nama lengkap
                    <input wire:model="staff.nama_s" type="text" class="mt-1 block min-h-11 w-full rounded-md border border-neutral-300 px-3 text-sm focus:border-red-800 focus:outline-none focus:ring-2 focus:ring-red-800/20">
                    @error('staff.nama_s') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                </label>
                <label class="text-sm font-medium text-neutral-700">
                    Username
                    <input wire:model="staff.username" type="text" class="mt-1 block min-h-11 w-full rounded-md border border-neutral-300 px-3 text-sm focus:border-red-800 focus:outline-none focus:ring-2 focus:ring-red-800/20">
                    @error('staff.username') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                </label>
                <label class="text-sm font-medium text-neutral-700">
                    Nomor staf
                    <input wire:model="staff.no" type="text" class="mt-1 block min-h-11 w-full rounded-md border border-neutral-300 px-3 text-sm focus:border-red-800 focus:outline-none focus:ring-2 focus:ring-red-800/20">
                    @error('staff.no') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                </label>
                <label class="text-sm font-medium text-neutral-700">
                    Hak akses
                    <select wire:model="staff.akses" class="mt-1 block min-h-11 w-full rounded-md border border-neutral-300 bg-white px-3 text-sm focus:border-red-800 focus:outline-none focus:ring-2 focus:ring-red-800/20">
                        <option value="guru">Guru</option>
                        <option value="admin">Admin</option>
                        <option value="dev">Developer</option>
                    </select>
                    @error('staff.akses') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                </label>
                <label class="text-sm font-medium text-neutral-700 sm:col-span-2">
                    Foto staf
                    <input wire:model="staff.foto_s" type="file" accept="image/jpeg,image/png" class="mt-1 block w-full rounded-md border border-neutral-300 bg-white p-2 text-sm">
                    <span class="mt-1 block text-xs font-normal text-neutral-500">JPG/PNG, maksimal 3 MB.</span>
                    @error('staff.foto_s') <span class="mt-1 block text-xs text-red-700">{{ $message }}</span> @enderror
                </label>
                <div class="flex gap-2 sm:col-span-2">
                    <button type="submit" wire:loading.attr="disabled" class="min-h-10 rounded-md bg-red-900 px-4 text-sm font-semibold text-white hover:bg-red-800 disabled:opacity-60">Simpan</button>
                    <button type="button" wire:click="cancelForm" class="min-h-10 rounded-md border border-neutral-300 px-4 text-sm font-medium text-neutral-700 hover:bg-neutral-50">Batal</button>
                </div>
            </form>
        </section>
    @endif

    <section aria-label="Daftar staf">
        <div class="overflow-x-auto border-y border-neutral-200">
            <table class="w-full min-w-[620px] text-left text-sm">
                <thead class="bg-neutral-100 text-xs uppercase text-neutral-600">
                    <tr>
                        <th scope="col" class="px-4 py-3 font-semibold">Nama</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Username</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Nomor</th>
                        <th scope="col" class="px-4 py-3 font-semibold">Akses</th>
                        <th scope="col" class="px-4 py-3 text-right font-semibold">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($staffList as $staffMember)
                        <tr class="bg-white">
                            <td class="px-4 py-3 font-medium text-neutral-900">{{ $staffMember->nama_s }}</td>
                            <td class="px-4 py-3 text-neutral-600">{{ $staffMember->username }}</td>
                            <td class="px-4 py-3 text-neutral-600">{{ $staffMember->no }}</td>
                            <td class="px-4 py-3 capitalize text-neutral-600">{{ $staffMember->akses }}</td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end gap-2">
                                    <button type="button" wire:click="edit('{{ $staffMember->id_staff }}')" class="min-h-9 rounded border border-neutral-300 px-3 text-xs font-semibold text-neutral-700 hover:border-red-800 hover:text-red-800">Ubah</button>
                                    <button type="button" wire:click="confirmDelete('{{ $staffMember->id_staff }}')" class="min-h-9 rounded border border-red-200 px-3 text-xs font-semibold text-red-800 hover:bg-red-50">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-neutral-500">Belum ada data staf.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($deleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-950/40 p-4" role="dialog" aria-modal="true" aria-labelledby="delete-staff-title">
            <div class="w-full max-w-sm bg-white p-6 shadow-xl">
                <h2 id="delete-staff-title" class="text-lg font-semibold text-neutral-950">Hapus staf?</h2>
                <p class="mt-2 text-sm text-neutral-600">Data staf ini akan dihapus dari sistem.</p>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="$set('deleteId', null)" class="min-h-10 rounded border border-neutral-300 px-4 text-sm font-medium text-neutral-700">Batal</button>
                    <button type="button" wire:click="delete" class="min-h-10 rounded bg-red-800 px-4 text-sm font-semibold text-white">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>