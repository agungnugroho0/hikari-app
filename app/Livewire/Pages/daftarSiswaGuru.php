<?php
namespace App\Livewire\Pages;

use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sensei')]
class daftarSiswaGuru extends Component
{
    public $siswa;
    public function mount(){
        $this->siswa = Staff::with(['kelas.core' => fn($q) => $q->where('status', 'siswa')->with('detail')])->findOrFail(Auth::user()->id_staff);
    }
    public function render(){return view('pages.daftar-siswa-guru');}
};