<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    protected $guarded=[];
    protected $table = 'tagihan';
    protected $primaryKey = 'id_t';
    protected $keyType = 'string';
public $incrementing = false;
    public $timestamps=false;

    public function tagihansiswa()
    {
        return $this->BelongsTo(Core::class,'nis','nis');
        }
        
        public function tagihanso()
        {return $this->BelongsTo(So::class,'id_so','id_so');}

        public function listtx()
        { return $this->hasMany(Transaksi::class,'id_t','id_t');}
}
