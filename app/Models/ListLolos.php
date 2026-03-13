<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListLolos extends Model
{
    /** @use HasFactory<\Database\Factories\ListLolosFactory> */
    use HasFactory;
    protected $table = "list_lolos";
    protected $guarded = [];
    protected $keyType = 'string';
    protected $primaryKey = 'id_lolos';

    public $timestamps = false;
    public $incrementing = false;

    public function corelist()
    {
        return $this->belongsTo(Core::class,'nis','nis');
    }

    public function detailso()
    {
        return $this->belongsTo(So::class,'id_so','id_so');
    }
}
