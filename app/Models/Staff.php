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

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $hidden = [
        'password',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $staff): void {
            if (blank($staff->id_staff)) {
                $staff->id_staff = static::generatePrefixedId('ST', static::class, 'id_staff');
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

    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_pengajar', 'id_staff');
    }
}
