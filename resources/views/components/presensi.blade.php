<div class="m-3">
    
    <x-kembali wire:navigate href="{{ route('home') }}"></x-kembali>
    {{-- @livewire('scan-absen') --}}
    <livewire:scan-absen />
    <livewire:daftar-hadir />
</div>