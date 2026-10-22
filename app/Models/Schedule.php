<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $primaryKey = 'id_jadwal';

    protected $fillable = ['title', 'date', 'method', 'note'];

    protected function casts(): array
    {
        return ['date' => 'datetime'];
    }
}
