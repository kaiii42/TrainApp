<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(): JsonResponse
    {
        $schedules = Schedule::with(['train', 'originStation', 'destinationStation'])
            ->where('is_active', true)
            ->get()
            ->map(fn (Schedule $s) => $this->formatSchedule($s));

        return response()->json([
            'success' => true,
            'data'    => $schedules,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'origin_station_id'      => ['required', 'integer', 'exists:stations,id'],
            'destination_station_id' => ['required', 'integer', 'exists:stations,id'],
            'date'                   => ['nullable', 'date'],
        ]);

        $schedules = Schedule::with(['train', 'originStation', 'destinationStation'])
            ->where('origin_station_id', $request->origin_station_id)
            ->where('destination_station_id', $request->destination_station_id)
            ->where('is_active', true)
            ->get()
            ->filter(function (Schedule $s) use ($request) {
                if (! $request->date) {
                    return true;
                }
                $dayOfWeek = strtolower(date('l', strtotime($request->date)));
                $available = array_map('strtolower', $s->available_days ?? []);
                return in_array($dayOfWeek, $available);
            })
            ->values()
            ->map(fn (Schedule $s) => $this->formatSchedule($s));

        return response()->json([
            'success' => true,
            'data'    => $schedules,
        ]);
    }

    public function show(Schedule $schedule): JsonResponse
    {
        $schedule->load(['train', 'originStation', 'destinationStation']);

        return response()->json([
            'success' => true,
            'data'    => $this->formatSchedule($schedule),
        ]);
    }

    // ── Helper ────────────────────────────────────────────────────────────────

    private function formatSchedule(Schedule $s): array
    {
        return [
            'id'                   => $s->id,
            'train_name'           => $s->train->name,
            'train_number'         => $s->train->train_number,
            'train_class'          => $s->train->class,
            'origin_station'       => $s->originStation->name,
            'origin_code'          => $s->originStation->code,
            'origin_time'          => $s->departure_time,
            'destination_station'  => $s->destinationStation->name,
            'destination_code'     => $s->destinationStation->code,
            'destination_time'     => $s->arrival_time,
            'duration'             => $s->duration,
            'price'                => (int) $s->price,
            'formatted_price'      => $s->formatted_price,
            'available_days'       => $s->available_days,
        ];
    }
}
