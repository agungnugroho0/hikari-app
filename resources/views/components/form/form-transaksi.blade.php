<div class="space-y-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $siswa->nis }}</p>
            <h2 class="text-lg font-semibold">Form Transaksi</h2>
        </div>
    </div>

    <form wire:submit.prevent="simpan" class="space-y-4 rounded p-4">
        <div>
            <label for="id_t" class="mb-1 block text-sm text-gray-600">Pilih Tagihan</label>
            <select wire:model.defer="form.id_t" id="id_t" class="w-full rounded border border-gray-300 p-2">
                <option value="">Pilih tagihan</option>
                @foreach ($siswa->listtagihan_siswa->where('status_tagihan', '!=', 'lunas') as $tagihan)
                    <option value="{{ $tagihan->id_t }}">
                        {{ $tagihan->nama_tagihan }} - Sisa Rp {{ number_format($tagihan->kekurangan_tagihan, 0, '.', ',') }}
                    </option>
                @endforeach
            </select>
            @error('form.id_t')
                <span class="text-sm text-red-700">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="tgl_transaksi" class="mb-1 block text-sm text-gray-600">Tanggal Transaksi</label>
            <input wire:model.defer="form.tgl_transaksi" type="date" id="tgl_transaksi"
                class="w-full rounded border border-gray-300 p-2">
            @error('form.tgl_transaksi')
                <span class="text-sm text-red-700">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="nominal" class="mb-1 block text-sm text-gray-600">Nominal Bayar</label>
            <input wire:model.defer="form.nominal" type="number" min="1" id="nominal"
                class="w-full rounded border border-gray-300 p-2" placeholder="Masukkan nominal">
            @error('form.nominal')
                <span class="text-sm text-red-700">{{ $message }}</span>
            @enderror
        </div>

        <div class="rounded bg-white p-3 text-sm text-gray-600">
            <p>Nama siswa: {{ optional($siswa->detail)->nama_lengkap }}</p>
            @php
                $selectedTagihan = $siswa->listtagihan_siswa->firstWhere('id_t', $form->id_t);
            @endphp
            @if ($selectedTagihan)
                <p>Tagihan: {{ $selectedTagihan->nama_tagihan }}</p>
                <p>Sisa tagihan: Rp {{ number_format($selectedTagihan->kekurangan_tagihan, 0, '.', ',') }}</p>
            @endif
        </div>

        <button type="submit" class="rounded bg-amber-950 px-4 py-2 text-white">
            Simpan Transaksi
        </button>
    </form>

    @if ($siswa->listtagihan_siswa->where('status_tagihan', '!=', 'lunas')->isEmpty())
        <p class="text-sm text-gray-500">Semua tagihan siswa ini sudah lunas.</p>
    @endif
</div>
