<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'schedule_id',
        'travel_date',
        'passenger_count',
        'total_price',
        'status',
        'payment_method',
        'paid_at',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'total_price' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function getFormattedTotalPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'cancelled' => 'Dibatalkan',
            'completed' => 'Selesai',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'paid' => 'info',
            'cancelled' => 'danger',
            'completed' => 'success',
            default => 'secondary',
        };
    }

    public static function generateBookingCode(): string
    {
        return 'TRN' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }
}
