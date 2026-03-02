<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UcoExport extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uco_batch_id',
        'export_code',
        'refinery_name',
        'volume',
        'price_per_liter',
        'total_value',
        'export_date',
        'status',
        'iscc_document',
        'locked_at',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'price_per_liter' => 'decimal:2',
        'total_value' => 'decimal:2',
        'export_date' => 'date',
        'status' => 'integer',
        'locked_at' => 'datetime',
    ];

    const STATUS_DRAFT = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_CANCELLED = 3;

    const STATUS_MAP = [
        self::STATUS_DRAFT => 'Draft',
        self::STATUS_CONFIRMED => 'Confirmed',
        self::STATUS_CANCELLED => 'Cancelled',
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

    public static function generateExportCode(): string
    {
        $year = date('Y');
        $lastExport = self::withTrashed()
            ->where('export_code', 'like', "EXP-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING(export_code, -4) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = $lastExport
            ? (int) substr($lastExport->export_code, -4) + 1
            : 1;

        return sprintf('EXP-%s-%04d', $year, $nextNumber);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_MAP[$this->status] ?? 'Draft';
    }

    public function batch()
    {
        return $this->belongsTo(UcoBatch::class, 'uco_batch_id');
    }

}
