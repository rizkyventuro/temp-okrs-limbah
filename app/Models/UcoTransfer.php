<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UcoTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uco_batch_id',
        'receiver_name',
        'receiver_company',
        'transfer_code',
        'transfer_qr_code',
        'status',
        'claimed_at',
    ];

    protected $casts = [
        'status' => 'integer',
        'claimed_at' => 'datetime',
    ];

    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELLED = 3;

    const STATUS_MAP = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_COMPLETED => 'Completed',
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

    public static function generateTransferCode(): string
    {
        $year = date('Y');
        $lastTransfer = self::withTrashed()
            ->where('transfer_code', 'like', "TRF-{$year}-%")
            ->orderByRaw('CAST(SUBSTRING(transfer_code, -4) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = $lastTransfer
            ? (int) substr($lastTransfer->transfer_code, -4) + 1
            : 1;

        return sprintf('TRF-%s-%04d', $year, $nextNumber);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_MAP[$this->status] ?? 'Pending';
    }

    public function batch()
    {
        return $this->belongsTo(UcoBatch::class, 'uco_batch_id');
    }
}
