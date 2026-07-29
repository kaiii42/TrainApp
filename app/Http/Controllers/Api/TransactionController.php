<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $transactions = Transaction::with(['schedule.train', 'schedule.originStation', 'schedule.destinationStation'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn (Transaction $t) => $this->formatTransaction($t));

        return response()->json([
            'success' => true,
            'data'    => $transactions,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'schedule_id'      => ['required', 'integer', 'exists:schedules,id'],
            'travel_date'      => ['required', 'date', 'after_or_equal:today'],
            'passenger_count'  => ['required', 'integer', 'min:1', 'max:10'],
            'payment_method'   => ['nullable', 'in:transfer,ewallet,credit_card'],
        ]);

        $schedule    = Schedule::findOrFail($data['schedule_id']);
        $totalPrice  = $schedule->price * $data['passenger_count'];

        $transaction = Transaction::create([
            'booking_code'    => Transaction::generateBookingCode(),
            'user_id'         => $request->user()->id,
            'schedule_id'     => $data['schedule_id'],
            'travel_date'     => $data['travel_date'],
            'passenger_count' => $data['passenger_count'],
            'total_price'     => $totalPrice,
            'status'          => 'pending',
            'payment_method'  => $data['payment_method'] ?? null,
        ]);

        $transaction->load(['schedule.train', 'schedule.originStation', 'schedule.destinationStation']);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat.',
            'data'    => $this->formatTransaction($transaction),
        ], 201);
    }

    public function show(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $transaction->load(['schedule.train', 'schedule.originStation', 'schedule.destinationStation']);

        return response()->json([
            'success' => true,
            'data'    => $this->formatTransaction($transaction),
        ]);
    }

    public function cancel(Request $request, Transaction $transaction): JsonResponse
    {
        if ($transaction->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if (! in_array($transaction->status, ['pending', 'paid'])) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak dapat dibatalkan.',
            ], 422);
        }

        $transaction->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibatalkan.',
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function formatTransaction(Transaction $t): array
    {
        $s = $t->schedule;

        return [
            'id'                    => $t->id,
            'booking_code'          => $t->booking_code,
            'train_name'            => $s->train->name,
            'train_number'          => $s->train->train_number,
            'train_class'           => $s->train->class,
            'origin_station'        => $s->originStation->name,
            'origin_code'           => $s->originStation->code,
            'origin_time'           => $s->departure_time,
            'destination_station'   => $s->destinationStation->name,
            'destination_code'      => $s->destinationStation->code,
            'destination_time'      => $s->arrival_time,
            'duration'              => $s->duration,
            'travel_date'           => $t->travel_date->toDateString(),
            'travel_date_formatted' => $t->travel_date->translatedFormat('d F Y'),
            'passenger_count'       => $t->passenger_count,
            'total_price'           => (int) $t->total_price,
            'formatted_total_price' => $t->formatted_total_price,
            'status'                => $t->status,
            'status_label'          => $t->status_label,
            'payment_method'        => $t->payment_method,
            'created_at'            => $t->created_at->toIso8601String(),
        ];
    }
}
