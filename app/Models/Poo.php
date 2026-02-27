<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Poo extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'address',
        'contact',
        'business_type',
        'total_volume',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'business_type' => 'integer',
        'total_volume' => 'decimal:2',
    ];

    const TYPE_RESTORAN = 1;
    const TYPE_UMKM = 2;
    const TYPE_RUMAH_TANGGA = 3;

    const TYPE_MAP = [
        self::TYPE_RESTORAN => 'Restoran',
        self::TYPE_UMKM => 'UMKM',
        self::TYPE_RUMAH_TANGGA => 'Rumah Tangga',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public static function getBusinessTypeValue(string $label): int
    {
        return array_flip(self::TYPE_MAP)[$label] ?? self::TYPE_RESTORAN;
    }

    public function getTypeAttribute(): string
    {
        return self::TYPE_MAP[$this->business_type] ?? 'Restoran';
    }

    public function getTotalCollectedAttribute(): float
    {
        return (float) $this->total_volume;
    }
}
