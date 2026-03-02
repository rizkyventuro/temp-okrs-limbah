<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UcoBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'poo_id',
        'batch_code',
        'volume',
        'collection_date',
        'photo',
        'notes',
        'qr_code',
        'status',
        'estimated_value',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'collection_date' => 'date',
        'status' => 'integer',
        'estimated_value' => 'decimal:2',
    ];

    const STATUS_AKTIF = 1;
    const STATUS_IN_TRANSFER = 2;
    const STATUS_TRANSFERRED = 3;
    const STATUS_FINAL_EXPORT = 4;
    const STATUS_EXPIRED = 5;

    const STATUS_MAP = [
        self::STATUS_AKTIF => 'Aktif',
        self::STATUS_IN_TRANSFER => 'In Transfer',
        self::STATUS_TRANSFERRED => 'Transferred',
        self::STATUS_FINAL_EXPORT => 'Final Export',
        self::STATUS_EXPIRED => 'Expired',
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

    public static function generateBatchCode(): string
    {
        $year = date('Y');
        $lastBatch = self::withTrashed()
            ->where('batch_code', 'like', "UCO-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING(batch_code, -4) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = $lastBatch
            ? (int) substr($lastBatch->batch_code, -4) + 1
            : 1;

        return sprintf('UCO-%s-%04d', $year, $nextNumber);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_MAP[$this->status] ?? 'Aktif';
    }

    public function poo()
    {
        return $this->belongsTo(Poo::class);
    }

    public function transfers()
    {
        return $this->hasMany(UcoTransfer::class, 'uco_batch_id');
    }

    public function exports()
    {
        return $this->hasMany(UcoExport::class, 'uco_batch_id');
    }
}
