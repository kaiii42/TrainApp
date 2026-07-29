<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use Illuminate\Http\JsonResponse;

class PromoBannerController extends Controller
{
    public function index(): JsonResponse
    {
        $banners = PromoBanner::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(fn (PromoBanner $b) => [
                'id'                  => $b->id,
                'title'               => $b->title,
                'description'         => $b->description,
                'image_url'           => $b->image,   // model column is 'image', API contract is 'image_url'
                'link'                => $b->link,
                'discount_percentage' => $b->discount_percentage,
            ]);

        return response()->json([
            'success' => true,
            'data'    => $banners,
        ]);
    }
}
