<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Station;
use App\Models\Train;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $malang = Station::where('code', 'ML')->first();
        $surabaya = Station::where('code', 'SGU')->first();
        $jakarta = Station::where('code', 'GMR')->first();
        $bandung = Station::where('code', 'BD')->first();
        $yogya = Station::where('code', 'YK')->first();
        $solo = Station::where('code', 'SLO')->first();

        $argoAnggrek = Train::where('train_number', 'KA-001')->first();
        $gajayana = Train::where('train_number', 'KA-004')->first();
        $bangunkarta = Train::where('train_number', 'KA-005')->first();
        $penataran = Train::where('train_number', 'KA-008')->first();

        $schedules = [
            // Malang - Surabaya
            ['train_id' => $penataran->id, 'origin_station_id' => $malang->id, 'destination_station_id' => $surabaya->id, 'departure_time' => '06:00', 'arrival_time' => '08:30', 'duration' => '2j 30m', 'price' => 75000],
            ['train_id' => $penataran->id, 'origin_station_id' => $malang->id, 'destination_station_id' => $surabaya->id, 'departure_time' => '14:00', 'arrival_time' => '16:30', 'duration' => '2j 30m', 'price' => 75000],
            ['train_id' => $bangunkarta->id, 'origin_station_id' => $malang->id, 'destination_station_id' => $surabaya->id, 'departure_time' => '08:00', 'arrival_time' => '10:00', 'duration' => '2j', 'price' => 125000],

            // Surabaya - Malang
            ['train_id' => $penataran->id, 'origin_station_id' => $surabaya->id, 'destination_station_id' => $malang->id, 'departure_time' => '09:00', 'arrival_time' => '11:30', 'duration' => '2j 30m', 'price' => 75000],
            ['train_id' => $penataran->id, 'origin_station_id' => $surabaya->id, 'destination_station_id' => $malang->id, 'departure_time' => '17:00', 'arrival_time' => '19:30', 'duration' => '2j 30m', 'price' => 75000],

            // Surabaya - Jakarta
            ['train_id' => $argoAnggrek->id, 'origin_station_id' => $surabaya->id, 'destination_station_id' => $jakarta->id, 'departure_time' => '08:00', 'arrival_time' => '17:00', 'duration' => '9j', 'price' => 450000],
            ['train_id' => $argoAnggrek->id, 'origin_station_id' => $surabaya->id, 'destination_station_id' => $jakarta->id, 'departure_time' => '20:00', 'arrival_time' => '05:00', 'duration' => '9j', 'price' => 450000],

            // Jakarta - Surabaya
            ['train_id' => $argoAnggrek->id, 'origin_station_id' => $jakarta->id, 'destination_station_id' => $surabaya->id, 'departure_time' => '07:00', 'arrival_time' => '16:00', 'duration' => '9j', 'price' => 450000],
            ['train_id' => $argoAnggrek->id, 'origin_station_id' => $jakarta->id, 'destination_station_id' => $surabaya->id, 'departure_time' => '19:00', 'arrival_time' => '04:00', 'duration' => '9j', 'price' => 450000],

            // Malang - Jakarta (Gajayana)
            ['train_id' => $gajayana->id, 'origin_station_id' => $malang->id, 'destination_station_id' => $jakarta->id, 'departure_time' => '17:00', 'arrival_time' => '05:30', 'duration' => '12j 30m', 'price' => 500000],
            ['train_id' => $gajayana->id, 'origin_station_id' => $jakarta->id, 'destination_station_id' => $malang->id, 'departure_time' => '18:00', 'arrival_time' => '06:30', 'duration' => '12j 30m', 'price' => 500000],

            // Jakarta - Bandung
            ['train_id' => $bangunkarta->id, 'origin_station_id' => $jakarta->id, 'destination_station_id' => $bandung->id, 'departure_time' => '06:00', 'arrival_time' => '09:00', 'duration' => '3j', 'price' => 150000],
            ['train_id' => $bangunkarta->id, 'origin_station_id' => $bandung->id, 'destination_station_id' => $jakarta->id, 'departure_time' => '16:00', 'arrival_time' => '19:00', 'duration' => '3j', 'price' => 150000],

            // Jakarta - Yogyakarta
            ['train_id' => $argoAnggrek->id, 'origin_station_id' => $jakarta->id, 'destination_station_id' => $yogya->id, 'departure_time' => '08:00', 'arrival_time' => '15:30', 'duration' => '7j 30m', 'price' => 350000],
            ['train_id' => $argoAnggrek->id, 'origin_station_id' => $yogya->id, 'destination_station_id' => $jakarta->id, 'departure_time' => '09:00', 'arrival_time' => '16:30', 'duration' => '7j 30m', 'price' => 350000],
        ];

        $allDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        foreach ($schedules as $schedule) {
            Schedule::create(array_merge($schedule, [
                'available_days' => $allDays,
                'is_active' => true,
            ]));
        }
    }
}
