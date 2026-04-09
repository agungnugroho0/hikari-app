<?php

namespace App\Livewire\Forms;

use App\Models\Kelas;
use App\Models\Settings as Set;
use App\Services\SettingsServices;
use Carbon\Carbon;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;

class SettingsForm extends Form
{
    use WithFileUploads;

    public $id_st;
    
    #[Validate('required')]
    public $nama_set;
    public $ket;        // file upload (Temporary)
    public $ket_path;   // string dari DB
    public $kelasOptions = [];

    public function setukk(){
    $ukk = Set::where('nama_set', 'ukk')->firstOrFail();
    $this->id_st = $ukk->id_st;
    $this->nama_set = $ukk->nama_set;
    $this->ket = $this->normalizeDateForInput($ukk->ket);
    }

    public function setnafuda(){
    $nafuda = Set::where('nama_set', 'nafuda')->firstOrFail();

    $this->id_st = $nafuda->id_st;
    $this->nama_set = $nafuda->nama_set;

    $this->ket_path = $nafuda->ket; // string
    $this->ket = null; // reset upload
    }

    public function setnafuda2(){
    $nafuda = Set::where('nama_set', 'nafuda2')->firstOrFail();

    $this->id_st = $nafuda->id_st;
    $this->nama_set = $nafuda->nama_set;

    $this->ket_path = $nafuda->ket; // string
    $this->ket = null; // reset upload
    }

    public function setKelasSaatIni(): void
    {
        $kelasSaatIni = Set::firstOrCreate(
            ['nama_set' => 'kelas_saat_ini'],
            [
                'id_st' => 'ST' . now()->format('YmdHis'),
                'ket' => '',
            ]
        );

        $this->id_st = $kelasSaatIni->id_st;
        $this->nama_set = $kelasSaatIni->nama_set;
        $this->ket = $kelasSaatIni->ket;
        $this->kelasOptions = Kelas::query()->orderBy('nama_kelas')->get();
    }
    

    protected SettingsServices $services;

    public function boot(SettingsServices $service){
        $this->services = $service;
    }
    public function editukk(){
        $this->validate();
        $this->services->updateukk(
            $this->only(['id_st','nama_set','ket'])
        );
    }

    public function editnafuda(){
        if (!($this->ket instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) {
        $this->ket = null;
        }
        $this->services->updatenfd([
            'id_st' => $this->id_st,
            'nama_set' => $this->nama_set,
            'ket' => $this->ket, // hanya file
        ]);
    }

    public function editKelasSaatIni(): void
    {
        $this->validate([
            'ket' => 'required|exists:kelas,id_kelas',
        ], [
            'ket.required' => 'Kelas saat ini wajib dipilih.',
            'ket.exists' => 'Kelas yang dipilih tidak valid.',
        ]);

        $this->services->updateKelasSaatIni([
            'id_st' => $this->id_st,
            'nama_set' => $this->nama_set,
            'ket' => $this->ket,
        ]);
    }

    protected function normalizeDateForInput(?string $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        foreach (['d/m/Y', 'Y-m-d'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Throwable $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }
    // public function editnafuda2(){
    //     if (!($this->ket instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) {
    //     $this->ket = null;
    //     }
    //     $this->services->updatenfd([
    //         'id_st' => $this->id_st,
    //         'nama_set' => $this->nama_set,
    //         'ket' => $this->ket, // hanya file
    //     ]);
    // }
}
