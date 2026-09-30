<?php

namespace App\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.developer')]
#[Title('Developer Console')]
class DeveloperDashboard extends Component
{
    public string $displayName = '';

    public string $username = '';

    public string $staffId = '';

    public function mount(): void
    {
        $staff = Auth::user();

        $this->displayName = (string) $staff->nama_s;
        $this->username = (string) $staff->username;
        $this->staffId = (string) $staff->id_staff;
    }

    public function render()
    {
        return view('pages.developer-dashboard');
    }
}
