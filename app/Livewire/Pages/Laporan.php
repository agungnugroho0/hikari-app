<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.admin')]
#[Title('Laporan')]
class Laporan extends BaseLaporan
{
    protected function initializeContext(): void
    {
    }

    public function render()
    {
        return view('pages.laporan');
    }
}
