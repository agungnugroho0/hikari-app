<div class="space-y-4 sm:space-y-5">
    <div x-data="{
        show: false,
        msg: '',
        }"
        x-on:kirim.window="
            msg = $event.detail.message ?? '';
            if (msg) {
                show = true;
                setTimeout(() => show = false, 3000);
            }
        "
        class="fixed right-5 top-5 z-50">
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="rounded bg-green-700 px-4 py-3 font-bold text-white shadow-lg">
            <span x-text="msg"></span>
        </div>
    </div>

    <x-dashboard-header
        :foto="$foto"
        :nama="$nama"
        :kelas="$sensei?->kelas?->nama_kelas"
    />

    <x-menu-bar />

    <section class="grid gap-3 sm:grid-cols-3">
        <x-stat-card
            title="Nama"
            :value="$sensei?->nama_s ?? '-'"
            description="Nama akun guru yang sedang aktif"
        />

        <x-stat-card
            title="Username"
            :value="$sensei?->username ?? '-'"
            description="Digunakan saat login ke sistem"
        />

        <x-stat-card
            title="Kelas"
            :value="$sensei?->kelas?->nama_kelas ?? '-'"
            description="Kelas yang sedang Anda ampu"
        />
    </section>

    <section class="rounded-[28px] border border-neutral-200 bg-white p-4 shadow-sm sm:p-5">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-900/70">Profil Guru</p>
            <h2 class="mt-1 text-lg font-semibold text-neutral-900">Kelola nama, foto, dan password akun</h2>
            <p class="mt-1 text-sm text-neutral-500">Perubahan akan langsung diterapkan pada akun guru yang sedang login.</p>
        </div>

        <livewire:forms.pengaturan-profil />
    </section>
</div>
