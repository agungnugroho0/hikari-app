<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class So extends Model
{
    use HasFactory;
    protected $guarded=[];
    protected $table="so";
    protected $primaryKey = 'id_so';
    protected $keyType = 'string';
    
    public $incrementing = false;
    public $timestamps=false;

    public function listlolos(){
        return $this->hasOne(ListLolos::class,'id_so','id_so');
    }

    public function list_job()
    {
        return $this->hasMany(JobFairModels::class,'id_so','id_so');
    }

    public function list_tagihanso()
    {
        return $this->hasMany(Tagihan::class,'id_so','id_so');
    }
}
