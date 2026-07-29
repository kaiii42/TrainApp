<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserXp extends Model
{
    protected $table = 'user_xp';

    protected $fillable = [
        'user_id',
        'total_xp',
        'current_level',
    ];

    /**
     * Level thresholds — total XP required to reach each level.
     * Do NOT change without explicit approval (.clauderules Rule 2).
     */
    public const LEVEL_THRESHOLDS = [
        1 =>    0,
        2 =>  100,
        3 =>  250,
        4 =>  500,
        5 => 1000,
    ];

    public const MAX_LEVEL = 5;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Derive the current level from total XP using the canonical thresholds.
     */
    public static function computeLevel(int $totalXp): int
    {
        $level = 1;
        foreach (self::LEVEL_THRESHOLDS as $lvl => $required) {
            if ($totalXp >= $required) {
                $level = $lvl;
            }
        }
        return $level;
    }

    /**
     * Build the full gamification payload for the API response.
     * Server-side only — Android never recomputes this.
     */
    public function toGamificationArray(int $dailyChatCount = 0): array
    {
        $level     = max(1, min(self::MAX_LEVEL, (int)($this->current_level ?? 1)));
        $totalXp   = $this->total_xp;
        $maxLevel  = self::MAX_LEVEL;
        $thresholds = self::LEVEL_THRESHOLDS;

        $levelStart = $thresholds[$level];
        $levelEnd   = isset($thresholds[$level + 1]) ? $thresholds[$level + 1] : $totalXp;

        $xpInCurrentLevel = $totalXp - $levelStart;
        $xpToNextLevel    = $level < $maxLevel ? ($levelEnd - $levelStart) : 0;
        $progressFraction = ($level < $maxLevel && $xpToNextLevel > 0)
            ? min(1.0, $xpInCurrentLevel / $xpToNextLevel)
            : 1.0;

        $remaining = max(0, 5 - $dailyChatCount);

        return [
            'total_xp'            => $totalXp,
            'current_level'       => $level,
            'xp_in_current_level' => $xpInCurrentLevel,
            'xp_to_next_level'    => $xpToNextLevel,
            'progress_fraction'   => round($progressFraction, 4),
            'daily_chat_count'    => $dailyChatCount,
            'remaining_quota'     => $remaining,
        ];
    }
}
