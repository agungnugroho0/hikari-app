<?php
namespace App\Livewire\Pages;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')] 
#[Title('Siswa')]
class Siswa extends Component
{
    
    public $idkelas = null ;

    public $selectedSiswa = null ;
    
    public $search = '';
    
    
    public function render()
    {
        return view('pages.siswa');
    }
    //
};
