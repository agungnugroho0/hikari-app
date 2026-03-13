<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
        protected $guarded=[];
    protected $table = 'tagihan';
    protected $primaryKey = 'id_t';
    protected $keyType = 'string';
public $incrementing = false;
    public $timestamps=false;

    public function listTagihan()
    {return $this->hasMany(Tagihan::class,'id_t','id_t');}
}
