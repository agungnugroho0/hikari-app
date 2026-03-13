<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobFairModels extends Model
{
    /** @use HasFactory<\Database\Factories\JobFairFactory> */
    use HasFactory;
    protected $guarded = [];
    protected $primaryKey = 'id_job';
    protected $keyType = 'string';
    protected $table = 'job_fair';
    
    public $incrementing = false;
    public $timestamps = false;

    public function list_ww()
    {
        return $this->hasMany(ListWawancara::class,'id_job','id_job');
    }

    public function list_so()
    {
        return $this->belongsTo(So::class,'id_so','id_so');
    }

}
