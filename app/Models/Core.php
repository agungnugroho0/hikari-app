<?php

namespace App\Models;

use App\Models\ListWawancara;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Core extends Model
{
    use HasFactory;
    protected $table = 'core';
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'nis',
        'id_kelas',
        'status',
        'foto'
    ] ;

    public function detail()
    {
        return $this->hasOne(DetailSiswa::class, 'nis', 'nis');
    }

    public function list_w()
    {
        return $this->hasMany(ListWawancara::class,'nis','nis');
    }

    public function kelas()
    {
        return $this->hasOne(Kelas::class,'id_kelas','id_kelas');
    }

    public function listlolos()
    {
        return $this->hasOne(ListLolos::class,'nis','nis');
    }

    public function listtagihan_siswa()
    {
        return $this->hasMany(Tagihan::class,'nis','nis');
    }
    public function listtx_siswa()
    {
        return $this->hasMany(Transaksi::class,'nis','nis');
    }

}
