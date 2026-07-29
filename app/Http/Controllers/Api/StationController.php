<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\JsonResponse;

class StationController extends Controller
{
    public function index(): JsonResponse
    {
        $stations = Station::where('is_active', true)->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data'    => $stations,
        ]);
    }

    public function show(Station $station): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $station,
        ]);
    }
}
