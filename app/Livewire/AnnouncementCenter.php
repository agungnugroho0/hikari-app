<?php

namespace App\Livewire;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AnnouncementCenter extends Component
{
    public array $announcements = [];

    public bool $showPopup = false;

    public function mount(): void
    {
        $user = Auth::user();
        $announcements = Announcement::published()
            ->where(function ($query) {
                $query->whereNull('akses')->orWhere('akses', Auth::user()->akses);
            })
            ->whereDoesntHave('reads', function ($query) {
                $query->where('id_staff', Auth::user()->getAuthIdentifier());
            })
            ->latest('published_at')
            ->get();

        foreach ($announcements as $announcement) {
            $announcement->reads()->firstOrCreate(
                ['id_staff' => $user->getAuthIdentifier()],
                ['read_at' => now()],
            );
        }

        $this->announcements = $announcements->map(fn (Announcement $announcement) => [
            'id' => $announcement->id,
            'title' => $announcement->title,
            'content' => $announcement->content,
            'published_at' => $announcement->published_at->format('d M Y'),
        ])->all();

        $this->showPopup = $this->announcements !== [];
    }

    public function close(): void
    {
        $this->showPopup = false;
    }

    public function render()
    {
        return view('livewire.announcement-center');
    }
}