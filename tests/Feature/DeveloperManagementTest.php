<?php

namespace Tests\Feature;

use App\Livewire\Pages\AdminAttendance;
use App\Livewire\Pages\DeveloperStaff;
use App\Models\Absen;
use App\Models\Core;
use App\Models\Kelas;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class DeveloperManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_can_create_update_and_delete_staff(): void
    {
        $developer = $this->createStaff('ST901', 'developer-management', 'dev');
        $this->actingAs($developer);

        Livewire::test(DeveloperStaff::class)
            ->call('create')
            ->set('staff.nama_s', 'Guru Baru')
            ->set('staff.username', 'guru-baru')
            ->set('staff.no', '081234567890')
            ->set('staff.akses', 'guru')
            ->call('save')
            ->assertHasNoErrors();

        $staff = Staff::query()->where('username', 'guru-baru')->firstOrFail();
        $this->assertSame('guru', $staff->akses);

        Livewire::test(DeveloperStaff::class)
            ->call('edit', $staff->id_staff)
            ->set('staff.nama_s', 'Guru Diperbarui')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('staff', [
            'id_staff' => $staff->id_staff,
            'nama_s' => 'Guru Diperbarui',
        ]);

        Livewire::test(DeveloperStaff::class)
            ->call('confirmDelete', $staff->id_staff)
            ->call('delete')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('staff', ['id_staff' => $staff->id_staff]);
        $this->assertDatabaseHas('staff', ['id_staff' => $developer->id_staff]);
    }

    public function test_developer_can_manage_staff_with_developer_access(): void
    {
        $developer = $this->createStaff('ST905', 'developer-access-test', 'dev');
        $this->actingAs($developer);

        Livewire::test(DeveloperStaff::class)
            ->call('create')
            ->set('staff.nama_s', 'Developer Baru')
            ->set('staff.username', 'developer-baru')
            ->set('staff.no', '081234567891')
            ->set('staff.akses', 'dev')
            ->call('save')
            ->assertHasNoErrors();

        $staff = Staff::query()->where('username', 'developer-baru')->firstOrFail();
        $this->assertSame('dev', $staff->akses);

        Livewire::test(DeveloperStaff::class)
            ->assertSee('Developer Baru')
            ->call('edit', $staff->id_staff)
            ->set('staff.nama_s', 'Developer Diperbarui')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('staff', [
            'id_staff' => $staff->id_staff,
            'nama_s' => 'Developer Diperbarui',
        ]);

        Livewire::test(DeveloperStaff::class)
            ->call('confirmDelete', $staff->id_staff)
            ->call('delete')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('staff', ['id_staff' => $staff->id_staff]);
    }

    public function test_admin_can_open_attendance_while_developer_cannot(): void
    {
        $admin = $this->createStaff('ST902', 'admin-attendance', 'admin');

        $this->actingAs($admin)
            ->get(route('pages::attendance'))
            ->assertOk()
            ->assertSee('Absensi Siswa');

        $developer = $this->createStaff('ST904', 'developer-no-attendance', 'dev');
        $this->actingAs($developer)
            ->get('/absensi')
            ->assertForbidden();

        $this->get('/dev/absensi')->assertNotFound();
    }

    public function test_developer_can_save_and_update_attendance_for_a_selected_date(): void
    {
        $developer = $this->createStaff('ST903', 'developer-attendance', 'dev');
        $kelas = Kelas::create([
            'id_kelas' => 'KLS901',
            'nama_kelas' => 'Kelas Tes',
        ]);
        $student = Core::create([
            'nis' => 'NIS-TEST-901',
            'id_kelas' => $kelas->id_kelas,
            'status' => 'siswa',
        ]);

        $this->actingAs($developer);

        Livewire::test(AdminAttendance::class)
            ->set('selectedNis', $student->nis)
            ->set('attendanceDate', '2026-04-19')
            ->call('saveAttendance', 'hadir')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('absen', [
            'nis' => $student->nis,
            'tgl' => '2026-04-19',
            'ket' => 'H',
        ]);

        Livewire::test(AdminAttendance::class)
            ->set('selectedNis', $student->nis)
            ->set('attendanceDate', '2026-04-19')
            ->call('saveAttendance', 'mensetsu')
            ->assertHasNoErrors();

        $this->assertSame(1, Absen::query()->where('nis', $student->nis)->count());
        $this->assertDatabaseHas('absen', [
            'nis' => $student->nis,
            'tgl' => '2026-04-19',
            'ket' => 'M',
        ]);
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