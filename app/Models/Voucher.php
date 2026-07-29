<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'user_id',
        'discount_percentage',
        'valid_from',
        'valid_until',
        'is_used',
        'is_expired',
    ];

    protected $casts = [
        'valid_from'  => 'date',
        'valid_until' => 'date',
        'is_used'     => 'boolean',
        'is_expired'  => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function toApiArray(): array
    {
        return [
            'code'                => $this->code,
            'discount_percentage' => $this->discount_percentage,
            'valid_from'          => $this->valid_from->toDateString(),
            'valid_until'         => $this->valid_until->toDateString(),
        ];
    }
}
