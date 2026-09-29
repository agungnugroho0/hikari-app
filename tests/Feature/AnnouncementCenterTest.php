<?php

namespace Tests\Feature;

use App\Livewire\AnnouncementCenter;
use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AnnouncementCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_displaying_a_popup_marks_its_announcements_as_read(): void
    {
        $staff = Staff::create([
            'id_staff' => 'ST008',
            'nama_s' => 'Admin Test',
            'username' => 'admin-test',
            'akses' => 'admin',
            'password' => Hash::make('secret'),
            'no' => '0000000000',
        ]);

        $announcement = Announcement::create([
            'title' => 'Fitur baru',
            'content' => 'Ringkasan fitur.',
            'published_at' => now(),
        ]);

        Livewire::actingAs($staff)
            ->test(AnnouncementCenter::class)
            ->assertSee('Pembaruan terbaru')
            ->assertSee('Fitur baru');

        $this->assertDatabaseHas('announcement_reads', [
            'announcement_id' => $announcement->id,
            'id_staff' => $staff->id_staff,
        ]);
    }

    public function test_popup_is_hidden_when_there_are_no_unread_announcements(): void
    {
        $staff = Staff::create([
            'id_staff' => 'ST009',
            'nama_s' => 'Admin Test',
            'username' => 'admin-test-2',
            'akses' => 'admin',
            'password' => Hash::make('secret'),
            'no' => '0000000000',
        ]);

        Livewire::actingAs($staff)
            ->test(AnnouncementCenter::class)
            ->assertDontSee('Pembaruan terbaru');

        $this->assertSame(0, AnnouncementRead::count());
    }
}
