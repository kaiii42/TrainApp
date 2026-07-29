<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'train_id',
        'origin_station_id',
        'destination_station_id',
        'departure_time',
        'arrival_time',
        'duration',
        'price',
        'available_days',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'available_days' => 'array',
        'is_active' => 'boolean',
    ];

    public function train(): BelongsTo
    {
        return $this->belongsTo(Train::class);
    }

    public function originStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'origin_station_id');
    }

    public function destinationStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'destination_station_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
