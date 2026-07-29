<?php

namespace Database\Seeders;

use App\Models\PromoBanner;
use Illuminate\Database\Seeder;

class PromoBannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Promo Akhir Tahun',
                'description' => 'Diskon 20% untuk semua rute kereta eksekutif. Berlaku hingga 31 Desember 2024.',
                'discount_percentage' => 20,
                'order' => 1,
            ],
            [
                'title' => 'Member Get Member',
                'description' => 'Ajak teman dan dapatkan cashback hingga Rp 100.000',
                'discount_percentage' => null,
                'order' => 2,
            ],
            [
                'title' => 'Early Bird Discount',
                'description' => 'Pesan 14 hari sebelum keberangkatan dan dapatkan diskon 15%',
                'discount_percentage' => 15,
                'order' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            PromoBanner::create(array_merge($banner, ['is_active' => true]));
        }
    }
}
