<?php

namespace Tests\Feature;

use App\Livewire\AnnouncementCenter;
use App\Livewire\Pages\Announcements as AnnouncementsPage;
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

    public function test_developer_can_manage_announcements(): void
    {
        $developer = Staff::create([
            'id_staff' => 'ST010',
            'nama_s' => 'Developer Test',
            'username' => 'developer-announcements',
            'akses' => 'dev',
            'password' => Hash::make('secret'),
            'no' => '0000000000',
        ]);

        $this->actingAs($developer)
            ->get(route('dev.announcements'))
            ->assertOk()
            ->assertSee('Pengumuman')
            ->assertSee('Developer Console');

        Livewire::actingAs($developer)
            ->test(AnnouncementsPage::class)
            ->set('title', 'Rilis developer')
            ->set('content', 'Ringkasan rilis.')
            ->set('akses', 'dev')
            ->call('publish')
            ->assertHasNoErrors()
            ->assertSee('Rilis developer');

        $announcement = Announcement::where('title', 'Rilis developer')->firstOrFail();
        $this->assertSame('dev', $announcement->akses);

        Livewire::actingAs($developer)
            ->test(AnnouncementsPage::class)
            ->call('togglePublication', $announcement->id);

        $this->assertNull($announcement->fresh()->published_at);

        Livewire::actingAs($developer)
            ->test(AnnouncementsPage::class)
            ->call('delete', $announcement->id);

        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }

    public function test_admin_cannot_access_the_moved_announcement_management(): void
    {
        $admin = Staff::create([
            'id_staff' => 'ST011',
            'nama_s' => 'Admin Test',
            'username' => 'admin-announcements',
            'akses' => 'admin',
            'password' => Hash::make('secret'),
            'no' => '0000000000',
        ]);

        $this->actingAs($admin)
            ->get(route('dev.announcements'))
            ->assertForbidden();

        $this->get('/pengumuman')->assertNotFound();
    }

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
