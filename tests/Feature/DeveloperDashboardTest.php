<?php

namespace Tests\Feature;

use App\Livewire\Auth\Login;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class DeveloperDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_can_view_the_dedicated_dashboard(): void
    {
        $developer = $this->createStaff('ST901', 'developer-test', 'dev');

        $this->actingAs($developer)
            ->get(route('dev.dashboard'))
            ->assertOk()
            ->assertSee('Developer Console')
            ->assertSee('developer-test')
            ->assertSee('Runtime aplikasi')
            ->assertSee('Stack aplikasi')
            ->assertSee('Tailwind CSS');
    }

    public function test_developer_with_the_initial_password_is_sent_to_the_dashboard(): void
    {
        $developer = Staff::create([
            'id_staff' => 'ST903',
            'nama_s' => 'Developer Test',
            'username' => 'developer-initial-password',
            'akses' => 'dev',
            'password' => Hash::make('123456'),
            'no' => '0000000000',
        ]);

        Livewire::test(Login::class)
            ->set('username', $developer->username)
            ->set('password', '123456')
            ->call('login')
            ->assertRedirect(route('dev.dashboard'));

        $this->assertAuthenticatedAs($developer);
        $this->get(route('dev.dashboard'))
            ->assertOk()
            ->assertSee('Runtime aplikasi');
    }

    public function test_developer_with_the_initial_password_can_open_the_dashboard(): void
    {
        $developer = Staff::create([
            'id_staff' => 'ST904',
            'nama_s' => 'Developer Test',
            'username' => 'developer-initial-dashboard',
            'akses' => 'dev',
            'password' => Hash::make('123456'),
            'no' => '0000000000',
        ]);

        $this->actingAs($developer)
            ->get(route('dev.dashboard'))
            ->assertOk()
            ->assertSee('Runtime aplikasi');
    }

    public function test_non_developer_cannot_view_the_developer_dashboard(): void
    {
        $admin = $this->createStaff('ST902', 'admin-test', 'admin');

        $this->actingAs($admin)
            ->get(route('dev.dashboard'))
            ->assertForbidden();
    }

    public function test_guest_is_redirected_to_login_from_the_developer_dashboard(): void
    {
        $this->get(route('dev.dashboard'))
            ->assertRedirect(route('login'));
    }

    private function createStaff(string $id, string $username, string $access): Staff
    {
        return Staff::create([
            'id_staff' => $id,
            'nama_s' => 'Developer Test',
            'username' => $username,
            'akses' => $access,
            'password' => Hash::make('secret'),
            'no' => '0000000000',
        ]);
    }
}
