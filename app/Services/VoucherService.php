<?php

namespace App\Services;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Str;

class VoucherService
{
    /**
     * Generate a unique voucher for the given user.
     * valid_until = valid_from + rand(7, 14) calendar days — Rule 4 constraint.
     */
    public function generateForUser(User $user): Voucher
    {
        do {
            $code = 'VCH-' . $user->id . '-' . now()->format('Ymd') . '-'
                  . strtoupper(Str::random(6));
        } while (Voucher::where('code', $code)->exists());

        return Voucher::create([
            'code'                => $code,
            'user_id'             => $user->id,
            'discount_percentage' => 10,
            'valid_from'          => now()->toDateString(),
            'valid_until'         => now()->addDays(rand(7, 14))->toDateString(),
        ]);
    }
}
