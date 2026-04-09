<?php

namespace App\Livewire\Forms;

use App\Models\Core;
use App\Services\SiswaServices;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;


class SiswaForm extends Form
{
    protected SiswaServices $services;

    public ?Core $siswa = null;
    use WithFileUploads;
    // tabel core
    #[Validate('required')]
    public $nis ='';
    
    #[Validate('required')]
    public $status ='';
    public $id_kelas ='';

    #[Validate('nullable|image|max:3072')] // 3MB Max
    public $foto;
    public $foto_lama;
    
    // tabel detail_siswa
    #[Validate('required')]
    public $nama_lengkap;

    #[Validate('required')]
    public $panggilan;

    #[Validate('required')]
    public $tgl_lahir;

    #[Validate('required')]
    public $gender;

    #[Validate('required')]
    public $tempat_lhr;

    public $alamat = '';

    #[Validate('required')]
    public $alamat_desa = '';

    #[Validate('required|numeric')]
    public $alamat_rt = '';

    #[Validate('required|numeric')]
    public $alamat_rw = '';

    #[Validate('required')]
    public $alamat_kecamatan = '';

    #[Validate('required')]
    public $alamat_kabupaten = '';

    #[Validate('required')]
    public $alamat_provinsi = '';

    #[Validate('required')]
    public $wa;

    public $wa_wali;

    #[Validate('required')]
    public $pernikahan;

    #[Validate('required')]
    public $agama;

    public ?string $submittedNis = null;

    public function boot(SiswaServices $service): void
    {
        $this->services = $service;
    }

    public function setModel(Core $siswa)
    {
        $this->siswa = $siswa;
        $this->nis = $siswa->nis;
        $this->status = $siswa->status;
        $this->foto_lama = $siswa->foto;
        $this->foto = null;
        $this->id_kelas = $siswa->id_kelas;
        $this->nama_kelas = $siswa->kelas->nama_kelas;
        $this->nama_lengkap = $siswa->detail->nama_lengkap;
        $this->panggilan = $siswa->detail->panggilan;
        $this->tgl_lahir = $siswa->detail->tgl_lahir->format('Y-m-d');
        $this->gender = $siswa->detail->gender;
        $this->tempat_lhr = $siswa->detail->tempat_lhr;
        $this->alamat = $siswa->detail->alamat;
        $this->fillAddressPartsFromString((string) $siswa->detail->alamat);
        $this->wa = $siswa->detail->wa;
        $this->wa_wali = $siswa->detail->wa_wali;
        $this->pernikahan = $siswa->detail->pernikahan;
        $this->agama = $siswa->detail->agama;
    }

    public function setCurrentClassFromSettings(): void
    {
        $this->id_kelas = (string) ($this->services->currentClassId() ?? '');
    }

    public function update()
    {
        $this->validate();
        $this->alamat = $this->buildAlamat();
        $path = $this->foto_lama;
            if ($this->foto) {

        // HAPUS FOTO LAMA DULU
        if ($this->foto_lama && Storage::disk('public')->exists($this->foto_lama)) {
            Storage::disk('public')->delete($this->foto_lama);
        }

        // RENAME
         $filename = Str::slug($this->nama_lengkap) . '.' . $this->foto->getClientOriginalExtension();
        // SIMPAN FOTO BARU
        $path = $this->foto->storeAs('foto',$filename,'public' );
    }
        $this->services->updateSiswa(
            $this->siswa,
            [
            'nis' => $this->nis,
            'status' => $this->status,
            'id_kelas' => $this->id_kelas,
            'foto' => $path,
            ],
            [
            'nama_lengkap' => $this->nama_lengkap,
            'panggilan' => $this->panggilan,
            'tgl_lahir' => $this->tgl_lahir,
            'gender' => $this->gender,
            'tempat_lhr' => $this->tempat_lhr,
            'alamat' => $this->alamat,
            'wa' => $this->wa,
            'wa_wali' => $this->wa_wali,
            'pernikahan' => $this->pernikahan,
            'agama' => $this->agama,
            ]
        );
    }

    public function storePublic(): string
    {
        $this->validate($this->publicRules(), $this->publicMessages());

        $this->alamat = $this->buildAlamat();
        $fotoPath = $this->foto ? $this->foto->store('foto', 'public') : null;

        $nis = $this->services->createPublic([
            'id_kelas' => $this->id_kelas,
            'foto' => $fotoPath,
            'nama_lengkap' => $this->nama_lengkap,
            'panggilan' => $this->panggilan,
            'tgl_lahir' => $this->tgl_lahir,
            'gender' => $this->gender,
            'tempat_lhr' => $this->tempat_lhr,
            'alamat' => $this->alamat,
            'wa' => $this->wa,
            'wa_wali' => $this->wa_wali,
            'pernikahan' => $this->pernikahan,
            'agama' => $this->agama,
        ]);

        $this->submittedNis = $nis;

        return $nis;
    }

    protected function buildAlamat(): string
    {
        return implode(', ', [
            'Desa ' . trim((string) $this->alamat_desa),
            'RT ' . trim((string) $this->alamat_rt),
            'RW ' . trim((string) $this->alamat_rw),
            'Kecamatan ' . trim((string) $this->alamat_kecamatan),
            'Kabupaten ' . trim((string) $this->alamat_kabupaten),
            'Provinsi ' . trim((string) $this->alamat_provinsi),
        ]);
    }

    protected function fillAddressPartsFromString(string $alamat): void
    {
        $segments = array_map('trim', explode(',', $alamat));

        $this->alamat_desa = $this->extractAddressValue($segments[0] ?? '', 'Desa');
        $this->alamat_rt = $this->extractAddressValue($this->findSegmentByPrefix($segments, 'RT') ?? '', 'RT');
        $this->alamat_rw = $this->extractAddressValue($this->findSegmentByPrefix($segments, 'RW') ?? '', 'RW');
        $this->alamat_kecamatan = $this->extractAddressValue($this->findSegmentByPrefix($segments, 'Kecamatan') ?? '', 'Kecamatan');
        $this->alamat_kabupaten = $this->extractAddressValue($this->findSegmentByPrefix($segments, 'Kabupaten') ?? '', 'Kabupaten');
        $this->alamat_provinsi = $this->extractAddressValue($this->findSegmentByPrefix($segments, 'Provinsi') ?? '', 'Provinsi');

        if (blank($this->alamat_desa) && filled($alamat)) {
            $this->alamat_desa = trim($alamat);
        }
    }

    protected function findSegmentByPrefix(array $segments, string $prefix): ?string
    {
        foreach ($segments as $segment) {
            if (Str::startsWith(Str::lower($segment), Str::lower($prefix))) {
                return $segment;
            }
        }

        return null;
    }

    protected function extractAddressValue(string $segment, string $prefix): string
    {
        $value = trim($segment);

        if (Str::startsWith(Str::lower($value), Str::lower($prefix))) {
            return trim(Str::substr($value, strlen($prefix)));
        }

        return $value;
    }

    protected function publicRules(): array
    {
        return [
            'id_kelas' => ['required', 'exists:kelas,id_kelas'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:3072'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'panggilan' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'gender' => ['required', 'in:L,P'],
            'tempat_lhr' => ['required', 'string', 'max:100'],
            'alamat_desa' => ['required', 'string', 'max:255'],
            'alamat_rt' => ['required', 'numeric'],
            'alamat_rw' => ['required', 'numeric'],
            'alamat_kecamatan' => ['required', 'string', 'max:255'],
            'alamat_kabupaten' => ['required', 'string', 'max:255'],
            'alamat_provinsi' => ['required', 'string', 'max:255'],
            'wa' => ['required', 'string', 'max:255'],
            'wa_wali' => ['nullable', 'string', 'max:255'],
            'pernikahan' => ['required', 'string', 'max:255'],
            'agama' => ['required', 'string', 'max:255'],
        ];
    }

    protected function publicMessages(): array
    {
        return [
            'id_kelas.required' => 'Kelas aktif belum disetel.',
            'id_kelas.exists' => 'Kelas aktif tidak valid.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, atau png.',
            'foto.max' => 'Ukuran foto maksimal 3MB.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'panggilan.required' => 'Panggilan wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lhr.required' => 'Tempat lahir wajib diisi.',
            'alamat_desa.required' => 'Desa wajib diisi.',
            'alamat_rt.required' => 'RT wajib diisi.',
            'alamat_rw.required' => 'RW wajib diisi.',
            'alamat_kecamatan.required' => 'Kecamatan wajib diisi.',
            'alamat_kabupaten.required' => 'Kabupaten wajib diisi.',
            'alamat_provinsi.required' => 'Provinsi wajib diisi.',
            'wa.required' => 'Nomor WhatsApp wajib diisi.',
            'agama.required' => 'Agama wajib diisi.',
        ];
    }

}
