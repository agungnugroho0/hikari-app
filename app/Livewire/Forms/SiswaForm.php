<?php

namespace App\Livewire\Forms;

use App\Models\Core;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;


class SiswaForm extends Form
{
    
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
    public $nama_lengkap,$panggilan,$tgl_lahir,$gender,$tempat_lhr,$alamat,$wa,$wa_wali,$pernikahan ;

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
        $this->wa = $siswa->detail->wa;
        $this->wa_wali = $siswa->detail->wa_wali;
        $this->pernikahan = $siswa->detail->pernikahan;
    }

    public function update()
    {
        $this->validate();
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
        $this->siswa->update(
            [ 
            'nis' => $this->nis,
            'status' => $this->status,
            'id_kelas' => $this->id_kelas,
            'foto' => $path,
            ]);
        $this->siswa->detail()->update(
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
            ]);
    }

}
