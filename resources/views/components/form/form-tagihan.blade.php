<div x-data="{
    formatRibuan(value) {
        const angka = String(value ?? '').replace(/\D/g, '');
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
}" class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $siswa->nis }}</p>
            <h2 class="text-lg font-semibold">Form Tagihan</h2>
        </div>
        <button wire:click="batal" type="button" class="rounded bg-gray-200 px-3 py-2 text-sm text-gray-700">
            Kembali
        </button>
    </div>

    <form wire:submit.prevent="simpan" class="space-y-4 rounded p-4">
        <div>
            <label for="tgl_terbit" class="mb-1 block text-sm text-gray-600">Tanggal Terbit</label>
            <input wire:model.defer="form.tgl_terbit" type="date" id="tgl_terbit"
                class="w-full rounded border border-gray-300 p-2">
            @error('form.tgl_terbit')
                <span class="text-sm text-red-700">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="nama_tagihan" class="mb-1 block text-sm text-gray-600">Nama Tagihan</label>
            <input wire:model.defer="form.nama_tagihan" type="text" id="nama_tagihan"
                class="w-full rounded border border-gray-300 p-2" placeholder="Masukkan nama tagihan">
            @error('form.nama_tagihan')
                <span class="text-sm text-red-700">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="total_tagihan" class="mb-1 block text-sm text-gray-600">Total Tagihan</label>
            <input type="text" id="total_tagihan" inputmode="numeric"
                x-data="{ display: formatRibuan($wire.form.total_tagihan) }"
                x-init="$watch(() => $wire.form.total_tagihan, value => display = formatRibuan(value))"
                x-model="display"
                x-on:input="
                    const raw = $event.target.value.replace(/\D/g, '');
                    display = formatRibuan(raw);
                    $wire.set('form.total_tagihan', raw);
                "
                class="w-full rounded border border-gray-300 p-2" placeholder="Masukkan total tagihan">
            @error('form.total_tagihan')
                <span class="text-sm text-red-700">{{ $message }}</span>
            @enderror
        </div>

        <div class="rounded bg-white p-3 text-sm text-gray-600">
            <p>Nama siswa: {{ optional($siswa->detail)->nama_lengkap }}</p>
            @if (optional($siswa->listlolos)->detailso?->nama_so)
                <p>SO: {{ optional($siswa->listlolos->detailso)->nama_so }}</p>
            @endif
        </div>

        <button type="submit" class="rounded bg-amber-950 px-4 py-2 text-white">
            Simpan Tagihan
        </button>
    </form>
</div>
