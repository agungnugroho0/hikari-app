<?php

namespace App\Livewire\Pages;

use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.sensei')]
#[Title('Profil Guru')]
class ProfilGuru extends Component
{
    public string $nama = '';

    public ?string $foto = null;

    public ?Staff $sensei = null;

    public function mount(): void
    {
        $this->nama = (string) Auth::user()->nama_s;
        $this->foto = Auth::user()->foto_s;
        $this->sensei = Staff::with('kelas')->findOrFail(Auth::user()->id_staff);
    }

    public function render()
    {
        return view('pages.profil-guru');
    }
}
