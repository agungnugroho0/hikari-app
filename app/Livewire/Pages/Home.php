<?php
namespace App\Livewire\Pages;

use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.sensei')]
class Home extends Component
{
    public $nama;
    public $foto;
    public $id_s;
    public $sensei;

    public function mount()
    {
        $this->id_s = Auth::user()->id_staff;
        $this->nama = Auth::user()->nama_s;
        $this->foto = Auth::user()->foto_s;
        $this->sensei = Staff::with('kelas')->findOrFail($this->id_s);

    }
    public function render(){
        return view('home');
    }
};