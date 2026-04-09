<div>
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
        class="fixed top-5 right-5 z-50">
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="rounded bg-green-700 px-4 py-3 font-bold text-white shadow-lg">
            <span x-text="msg"></span>
        </div>
    </div>
    <div>
        <h1 class="text-2xl font-bold text-neutral-900 mb-2">Settings</h1>
    </div>
    <livewire:forms.pengaturan-profil />
    <livewire:form.kelas-saat-ini />
    <livewire:form.ukk />
    <livewire:form.nafuda />
    <livewire:form.nafuda2 />
</div>
