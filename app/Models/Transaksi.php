<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaksi extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $table = 'transaksi';
    protected $primaryKey = 'id_tx';
    protected $keyType = 'string';
public $incrementing = false;
    public $timestamps=false;

    public function listTagihan()
    {return $this->hasMany(Tagihan::class,'id_t','id_t');}
}
