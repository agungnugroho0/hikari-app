<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Settings')]
class Setelan extends Component
{
    public function mount() {}

    public function render()
    {
        return view('pages.setelan');
    }
}
