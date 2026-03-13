<?php

namespace App\Models;

// use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staff extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\StaffFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $table = 'staff';

    protected $primaryKey = 'id_staff';

    public $timestamps = false;

    protected $hidden = [
        'password',
    ];
}
