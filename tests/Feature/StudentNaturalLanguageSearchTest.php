<?php

namespace Tests\Feature;

use App\Models\Core;
use App\Models\DetailSiswa;
use App\Models\Kelas;
use App\Services\StudentNaturalLanguageSearch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class StudentNaturalLanguageSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_it_parses_gender_and_height_without_generating_sql(): void
    {
        $service = app(StudentNaturalLanguageSearch::class);

        $parsed = $service->parse('carikan siswa cowok dengan tinggi minimal 160');

        $this->assertSame('search_students', $parsed['intent']);
        $this->assertSame([
            ['field' => 'jenis_kelamin', 'operator' => '=', 'value' => 'L'],
        ], $parsed['filters']);
        $this->assertStringContainsString('tinggi badan belum tersedia', $parsed['notices'][0]);
    }

    public function test_it_filters_students_by_natural_address_gender_status_and_name(): void
    {
        $kelas = Kelas::create(['id_kelas' => 'KLS001', 'nama_kelas' => 'Kelas A']);
        $ahmad = $this->createStudent($kelas->id_kelas, 'NIS001', 'Ahmad Rizki', 'L', 'siswa', 'Desa Sukamaju, RT 1, RW 2, Kecamatan Boja, Kabupaten Kendal, Provinsi Jawa Tengah');
        $this->createStudent($kelas->id_kelas, 'NIS002', 'Budi Santoso', 'L', 'siswa', 'Desa Sukamaju, RT 1, RW 2, Kecamatan Tembalang, Kabupaten Semarang, Provinsi Jawa Tengah');
        $this->createStudent($kelas->id_kelas, 'NIS003', 'Aisyah Putri', 'P', 'cuti', 'Desa Sukamaju, RT 1, RW 2, Kecamatan Boja, Kabupaten Kendal, Provinsi Jawa Tengah');

        $service = app(StudentNaturalLanguageSearch::class);

        $parsed = $service->parse('cari Ahmad yang tinggal di Kendal');
        $results = $service->apply(Core::query()->with('detail')->where('status', 'siswa'), $parsed)->get();
        $this->assertTrue($results->pluck('nis')->contains($ahmad->nis));
        $this->assertCount(1, $results);

        $parsed = $service->parse('carikan siswa yang tinggal di semarng');
        $results = $service->apply(Core::query()->with('detail'), $parsed)->pluck('nis');
        $this->assertEquals(['NIS002'], $results->values()->all());

        $parsed = $service->parse('cari siswa status cuti');
        $results = $service->apply(Core::query()->with('detail'), $parsed)->pluck('nis');
        $this->assertEquals(['NIS003'], $results->values()->all());

        $parsed = $service->parse('cari siswa dari kecamatan boja');
        $results = $service->apply(Core::query()->with('detail')->where('status', 'siswa'), $parsed)->pluck('nis');
        $this->assertEquals(['NIS001'], $results->values()->all());

        $parsed = $service->parse('cowok rumahnya di kendal');
        $this->assertSame([
            ['field' => 'jenis_kelamin', 'operator' => '=', 'value' => 'L'],
            ['field' => 'alamat', 'operator' => 'contains', 'value' => 'Kendal'],
        ], $parsed['filters']);

        $results = $service->apply(Core::query()->with('detail')->where('status', 'siswa'), $parsed)->pluck('nis');
        $this->assertEquals(['NIS001'], $results->values()->all());
    }

    public function test_plain_keyword_search_still_works(): void
    {
        $kelas = Kelas::create(['id_kelas' => 'KLS001', 'nama_kelas' => 'Kelas A']);
        $this->createStudent($kelas->id_kelas, 'NIS001', 'Ahmad Rizki', 'L', 'siswa', 'Desa Sukamaju, RT 1, RW 2, Kecamatan Boja, Kabupaten Kendal, Provinsi Jawa Tengah');
        $this->createStudent($kelas->id_kelas, 'NIS002', 'Budi Santoso', 'L', 'siswa', 'Desa Sukamaju, RT 1, RW 2, Kecamatan Tembalang, Kabupaten Semarang, Provinsi Jawa Tengah');

        $service = app(StudentNaturalLanguageSearch::class);
        $parsed = $service->parse('Ahmad');

        $this->assertSame('text', $parsed['mode']);

        $results = $service->applyTextSearch(Core::query()->with('detail'), 'Ahmad')->pluck('nis');

        $this->assertEquals(['NIS001'], $results->values()->all());
    }

    public function test_it_filters_students_by_virtual_age_from_birth_date(): void
    {
        Carbon::setTestNow('2026-10-07');

        $kelas = Kelas::create(['id_kelas' => 'KLS001', 'nama_kelas' => 'Kelas A']);
        $this->createStudent($kelas->id_kelas, 'AGE19', 'Usia Sembilan Belas', 'L', 'siswa', 'Kabupaten Kendal', '2006-10-08');
        $this->createStudent($kelas->id_kelas, 'AGE20A', 'Usia Dua Puluh A', 'L', 'siswa', 'Kabupaten Kendal', '2006-10-07');
        $this->createStudent($kelas->id_kelas, 'AGE20B', 'Usia Dua Puluh B', 'P', 'siswa', 'Kabupaten Semarang', '2005-10-08');
        $this->createStudent($kelas->id_kelas, 'AGE21', 'Usia Dua Satu', 'L', 'siswa', 'Kabupaten Kendal', '2005-10-07');
        $this->createStudent($kelas->id_kelas, 'AGE25', 'Usia Dua Lima', 'P', 'siswa', 'Kabupaten Semarang', '2000-10-08');
        $this->createStudent($kelas->id_kelas, 'AGE26', 'Usia Dua Enam', 'P', 'siswa', 'Kabupaten Kendal', '2000-10-07');
        $this->createStudent($kelas->id_kelas, 'AGE18P', 'Perempuan Kendal', 'P', 'siswa', 'Kabupaten Kendal', '2008-10-07');
        $this->createStudent($kelas->id_kelas, 'AGE22P', 'Perempuan Kendal Senior', 'P', 'siswa', 'Kabupaten Kendal', '2004-10-08');

        $service = app(StudentNaturalLanguageSearch::class);

        $this->assertSearchNis($service, 'cari siswa umur 20 tahun', ['AGE20A', 'AGE20B']);
        $this->assertSearchNis($service, 'cari siswa umur minimal 20 tahun', ['AGE20A', 'AGE20B', 'AGE21', 'AGE25', 'AGE26', 'AGE22P']);
        $this->assertSearchNis($service, 'cari siswa umur 20 tahun ke atas', ['AGE20A', 'AGE20B', 'AGE21', 'AGE25', 'AGE26', 'AGE22P']);
        $this->assertSearchNis($service, 'cari siswa umur maksimal 25 tahun', ['AGE19', 'AGE20A', 'AGE20B', 'AGE21', 'AGE25', 'AGE18P', 'AGE22P']);
        $this->assertSearchNis($service, 'cari siswa umur di bawah 20 tahun', ['AGE19', 'AGE18P']);
        $this->assertSearchNis($service, 'cari siswa umur antara 18 sampai 22 tahun', ['AGE19', 'AGE20A', 'AGE20B', 'AGE21', 'AGE18P', 'AGE22P']);
        $this->assertSearchNis($service, 'cari siswa cowok umur minimal 20 tahun', ['AGE20A', 'AGE21']);
        $this->assertSearchNis($service, 'cari siswa perempuan umur 18 sampai 22 tahun dari Kendal', ['AGE18P', 'AGE22P']);
    }

    protected function assertSearchNis(StudentNaturalLanguageSearch $service, string $input, array $expected): void
    {
        $parsed = $service->parse($input);
        $results = $service->apply(Core::query()->with('detail')->orderBy('nis'), $parsed)->pluck('nis')->all();

        sort($expected);
        sort($results);

        $this->assertSame($expected, $results, $input);
    }

    protected function createStudent(string $classId, string $nis, string $name, string $gender, string $status, string $address, string $birthDate = '2000-01-01'): Core
    {
        $student = Core::create([
            'nis' => $nis,
            'id_kelas' => $classId,
            'status' => $status,
            'foto' => null,
        ]);

        DetailSiswa::create([
            'nis' => $nis,
            'nama_lengkap' => $name,
            'panggilan' => strtok($name, ' '),
            'tgl_lahir' => $birthDate,
            'gender' => $gender,
            'tempat_lhr' => 'Kendal',
            'alamat' => $address,
            'wa' => '081234567890',
            'wa_wali' => '081234567891',
            'pernikahan' => 'Belum menikah',
            'agama' => 'Islam',
        ]);

        return $student;
    }
}
