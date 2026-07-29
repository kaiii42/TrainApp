<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatLog;
use App\Models\Schedule;
use App\Models\Station;
use App\Models\UserXp;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private const DAILY_LIMIT = 5;
    private const XP_PER_CHAT = 10;

    /**
     * POST /api/chat
     *
     * Rule 1: Enforces daily chat limit of 5 per user per calendar day.
     *         Admin users (role='admin') bypass the limit for testing.
     * Rule 2: Awards 10 XP per successful send (non-admin only).
     * Rule 3: Generates a voucher on exactly the 5th message of the day (non-admin only).
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $user    = $request->user();
        $isAdmin = $user->isAdmin();

        // ── Rule 1: quota check (bypassed for admin) ──────────────────────────
        $chatLog = ChatLog::firstOrCreate(
            ['user_id' => $user->id, 'date' => today()],
            ['message_count' => 0]
        );

        if (!$isAdmin && $chatLog->message_count >= self::DAILY_LIMIT) {
            return response()->json([
                'message'   => 'Batas chat harian tercapai. Coba lagi besok.',
                'remaining' => 0,
            ], 429);
        }

        // ── Call Gemini with live DB context ─────────────────────────────────
        $reply = $this->callGemini($request->message);

        // ── Rule 1: atomic increment ──────────────────────────────────────────
        $chatLog->increment('message_count');
        $fresh     = $chatLog->fresh();
        $count     = $fresh->message_count;
        $remaining = $isAdmin ? self::DAILY_LIMIT : max(0, self::DAILY_LIMIT - $count);

        // ── Rule 2: award XP (non-admin only) ────────────────────────────────
        if (!$isAdmin) {
            $xp = UserXp::firstOrCreate(['user_id' => $user->id]);
            $xp->increment('total_xp', self::XP_PER_CHAT);
            $xp->current_level = UserXp::computeLevel($xp->fresh()->total_xp);
            $xp->save();
        }

        // ── Rule 3: voucher on exactly the 5th message (non-admin only) ───────
        $voucher = null;
        if (!$isAdmin && $count === self::DAILY_LIMIT) {
            try {
                $generated = app(VoucherService::class)->generateForUser($user);
                $voucher   = $generated->toApiArray();
            } catch (\Throwable $e) {
                Log::error('Voucher generation failed', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
                // Do NOT fail the chat response on voucher error.
            }
        }

        return response()->json([
            'success'   => true,
            'reply'     => $reply,
            'remaining' => $remaining,
            'voucher'   => $voucher,
        ]);
    }

    // ── Private: build live context from DB ──────────────────────────────────

    private function buildDbContext(): string
    {
        $stations = Station::where('is_active', true)
            ->select('name', 'code', 'city')
            ->get()
            ->map(fn($s) => "{$s->name} ({$s->code}) — {$s->city}")
            ->implode('; ');

        $schedules = Schedule::where('is_active', true)
            ->with(['train', 'originStation', 'destinationStation'])
            ->get()
            ->map(function ($s) {
                $days = $s->available_days ? implode(',', $s->available_days) : 'setiap hari';
                return sprintf(
                    '%s [%s] %s→%s dep %s arr %s %s Rp%s hari:%s',
                    $s->train->name,
                    $s->train->class,
                    $s->originStation->code,
                    $s->destinationStation->code,
                    $s->departure_time,
                    $s->arrival_time,
                    $s->duration,
                    number_format($s->price, 0, ',', '.'),
                    $days
                );
            })
            ->implode(' | ');

        return "STASIUN: {$stations}. JADWAL: {$schedules}.";
    }

    // ── Private: Gemini HTTP call with key fallback ───────────────────────────

    private function callGemini(string $userMessage): string
    {
        try {
            $context = $this->buildDbContext();
        } catch (\Throwable $e) {
            Log::error('buildDbContext failed', ['error' => $e->getMessage()]);
            $context = '';
        }

        $prompt = implode("\n", array_filter([
            'Kamu adalah asisten tiket kereta api yang ramah.',
            $context ? 'Gunakan data berikut untuk menjawab pertanyaan: ' . $context : '',
            'Pertanyaan User: ' . $userMessage,
        ]));

        $keys = config('services.gemini.keys', []);

        foreach ($keys as $index => $key) {
            try {
                $response = Http::timeout(45)->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $key,
                    [
                        'contents'         => [
                            ['parts' => [['text' => $prompt]]],
                        ],
                        'generationConfig' => [
                            'thinkingConfig' => ['thinkingBudget' => 0],
                        ],
                    ]
                );

                if ($response->successful()) {
                    return data_get(
                        $response->json(),
                        'candidates.0.content.parts.0.text',
                        'Maaf, AI tidak memberikan jawaban.'
                    );
                }

                Log::warning('Gemini key failed, trying next', [
                    'key_index' => $index,
                    'status'    => $response->status(),
                    'body'      => substr($response->body(), 0, 300),
                ]);
            } catch (\Throwable $e) {
                Log::error('Gemini call exception', [
                    'key_index' => $index,
                    'error'     => $e->getMessage(),
                ]);
            }
        }

        return 'Maaf, terjadi kesalahan pada layanan AI.';
    }
}
