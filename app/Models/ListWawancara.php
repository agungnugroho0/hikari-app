<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListWawancara extends Model
{
    /** @use HasFactory<\Database\Factories\ListWawancaraFactory> */
    use HasFactory;
    protected $table = 'list_wawancara';
    protected $primaryKey = 'id_list';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    public function corelist()
    {
        return $this->belongsTo(Core::class,'nis','nis');
    }

    public function joblist()
    {
        return $this->belongsTo(JobFairModels::class,'id_job','id_job');
    }

    
}
