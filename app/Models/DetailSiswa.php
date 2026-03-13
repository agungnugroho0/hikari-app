<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailSiswa extends Model
{
    use HasFactory;
    protected $table="detail_siswa";
    protected $primaryKey = 'nis';
    protected $keyType = 'string';
    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;

    public function core()
    {
        return $this->belongsTo(Core::class, 'nis', 'nis');
    }

    protected $casts = [
        'tgl_lahir' => 'date',
    ];
}
