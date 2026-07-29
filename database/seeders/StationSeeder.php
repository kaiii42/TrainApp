<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    public function run(): void
    {
        $stations = [
            ['name' => 'Stasiun Kota Baru Malang', 'code' => 'ML', 'city' => 'Malang', 'latitude' => -7.977, 'longitude' => 112.637],
            ['name' => 'Stasiun Kota Lama Malang', 'code' => 'MLK', 'city' => 'Malang', 'latitude' => -7.994, 'longitude' => 112.632],
            ['name' => 'Stasiun Blimbing', 'code' => 'BLI', 'city' => 'Malang', 'latitude' => -7.940, 'longitude' => 112.645],
            ['name' => 'Stasiun Surabaya Gubeng', 'code' => 'SGU', 'city' => 'Surabaya', 'latitude' => -7.265, 'longitude' => 112.752],
            ['name' => 'Stasiun Surabaya Pasar Turi', 'code' => 'SBI', 'city' => 'Surabaya', 'latitude' => -7.248, 'longitude' => 112.731],
            ['name' => 'Stasiun Gambir', 'code' => 'GMR', 'city' => 'Jakarta', 'latitude' => -6.177, 'longitude' => 106.830],
            ['name' => 'Stasiun Jakarta Kota', 'code' => 'JAKK', 'city' => 'Jakarta', 'latitude' => -6.138, 'longitude' => 106.814],
            ['name' => 'Stasiun Bandung', 'code' => 'BD', 'city' => 'Bandung', 'latitude' => -6.913, 'longitude' => 107.604],
            ['name' => 'Stasiun Yogyakarta', 'code' => 'YK', 'city' => 'Yogyakarta', 'latitude' => -7.789, 'longitude' => 110.361],
            ['name' => 'Stasiun Solo Balapan', 'code' => 'SLO', 'city' => 'Solo', 'latitude' => -7.568, 'longitude' => 110.821],
            ['name' => 'Stasiun Semarang Tawang', 'code' => 'SMT', 'city' => 'Semarang', 'latitude' => -6.964, 'longitude' => 110.427],
            ['name' => 'Stasiun Cirebon', 'code' => 'CN', 'city' => 'Cirebon', 'latitude' => -6.706, 'longitude' => 108.557],
        ];

        foreach ($stations as $station) {
            Station::create(array_merge($station, ['is_active' => true]));
        }
    }
}
