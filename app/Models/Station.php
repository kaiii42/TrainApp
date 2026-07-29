<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'city',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    public function originSchedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'origin_station_id');
    }

    public function destinationSchedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'destination_station_id');
    }
}
