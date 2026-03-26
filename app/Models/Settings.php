<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    /** @use HasFactory<\Database\Factories\SettingsFactory> */
    use HasFactory;
    protected $guarded=[];
    protected $table="settings";
    protected $primaryKey = 'id_st';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps=false;
}
