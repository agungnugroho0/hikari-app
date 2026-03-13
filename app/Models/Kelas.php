<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $incrementing = false; // atau false kalau bukan auto increment
    // protected $keyType = 'string';  // atau string kalau UUID

    public function core()
    {
        return $this->belongsTo(Core::class);
    }
}
