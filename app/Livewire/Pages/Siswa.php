<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Siswa')]
class Siswa extends Component
{
    public $idkelas = null;

    public $selectedSiswa = null;

    public $search = '';

    public function render()
    {
        return view('pages.siswa');
    }
    //
}
