<?php

namespace App\Livewire\Forms;

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

    public function setukk(){
    $ukk = Set::where('nama_set', 'ukk')->firstOrFail();
    $this->id_st = $ukk->id_st;
    $this->nama_set = $ukk->nama_set;
    $this->ket = Carbon::createFromFormat('d/m/Y', $ukk->ket)->format('Y-m-d'); //convert string to date
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
