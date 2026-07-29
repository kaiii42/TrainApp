<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Train extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'train_number',
        'class',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function getClassLabelAttribute(): string
    {
        return match($this->class) {
            'ekonomi' => 'Ekonomi',
            'bisnis' => 'Bisnis',
            'eksekutif' => 'Eksekutif',
            default => $this->class,
        };
    }
}
