<?php

namespace App\Livewire\Pages;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Pengumuman')]
class Announcements extends Component
{
    public string $title = '';

    public string $content = '';

    public string $akses = '';

    public function publish(): void
    {
        abort_unless(Auth::user()?->akses === 'admin', 403);

        $data = $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'akses' => ['nullable', 'in:admin,guru,dev'],
        ]);

        Announcement::create([
            ...$data,
            'akses' => $data['akses'] ?: null,
            'published_at' => now(),
        ]);

        $this->reset(['title', 'content', 'akses']);
        session()->flash('announcement-status', 'Pengumuman berhasil diterbitkan.');
    }

    public function togglePublication(int $announcementId): void
    {
        abort_unless(Auth::user()?->akses === 'admin', 403);

        $announcement = Announcement::findOrFail($announcementId);
        $announcement->update([
            'published_at' => $announcement->published_at ? null : now(),
        ]);
    }

    public function delete(int $announcementId): void
    {
        abort_unless(Auth::user()?->akses === 'admin', 403);

        Announcement::findOrFail($announcementId)->delete();
    }

    public function render()
    {
        return view('pages.announcements', [
            'announcements' => Announcement::latest()->get(),
        ]);
    }
}