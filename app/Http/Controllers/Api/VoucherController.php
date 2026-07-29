<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * GET /api/vouchers — list active (not expired, not used) vouchers for the user.
     * Rule 5: filters by scheduler-maintained is_expired flag — never re-derives from valid_until.
     */
    public function index(Request $request): JsonResponse
    {
        $vouchers = Voucher::where('user_id', $request->user()->id)
                           ->where('is_expired', false)
                           ->where('is_used', false)
                           ->orderBy('created_at', 'desc')
                           ->get()
                           ->map(fn (Voucher $v) => $v->toApiArray());

        return response()->json([
            'success' => true,
            'data'    => $vouchers,
        ]);
    }

    /**
     * POST /api/vouchers/{code}/redeem — mark a voucher as used.
     */
    public function redeem(Request $request, string $code): JsonResponse
    {
        $voucher = Voucher::where('code', $code)
                          ->where('user_id', $request->user()->id)
                          ->first();

        if (! $voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak ditemukan.',
            ], 404);
        }

        if ($voucher->is_expired) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah kedaluwarsa.',
            ], 422);
        }

        if ($voucher->is_used) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher sudah digunakan.',
            ], 422);
        }

        $voucher->update(['is_used' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil digunakan.',
            'data'    => $voucher->fresh()->toApiArray(),
        ]);
    }
}
