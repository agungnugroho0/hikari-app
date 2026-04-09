<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected $table = 'kelas';

    protected $primaryKey = 'id_kelas';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $kelas): void {
            if (blank($kelas->id_kelas)) {
                $kelas->id_kelas = static::generatePrefixedId('KLS', static::class, 'id_kelas');
            }
        });
    }

    protected static function generatePrefixedId(string $prefix, string $modelClass, string $column): string
    {
        $latest = $modelClass::query()
            ->where($column, 'like', $prefix.'%')
            ->orderBy($column, 'desc')
            ->lockForUpdate()
            ->first();

        $nextNumber = $latest
            ? ((int) substr($latest->{$column}, strlen($prefix))) + 1
            : 1;

        return $prefix.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
    }

    public function core()
    {
        return $this->hasMany(Core::class, 'id_kelas', 'id_kelas');
    }

    public function pengajar()
    {
        return $this->belongsTo(Staff::class, 'id_pengajar', 'id_staff');
    }

    public function guru()
    {
        return $this->belongsTo(Staff::class, 'id_pengajar', 'id_staff');
    }
}
