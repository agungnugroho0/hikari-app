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
        $maxNumber = (int) $modelClass::query()
            ->where($column, 'like', $prefix.'%')
            ->selectRaw("MAX(CAST(SUBSTRING($column, ?) AS UNSIGNED)) as max_number", [strlen($prefix) + 1])
            ->lockForUpdate()
            ->value('max_number');

        do {
            $maxNumber++;
            $id = $prefix.str_pad((string) $maxNumber, 3, '0', STR_PAD_LEFT);
        } while ($modelClass::query()->where($column, $id)->exists());

        return $id;
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
