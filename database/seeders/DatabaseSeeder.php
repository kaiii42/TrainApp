<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            StationSeeder::class,
            TrainSeeder::class,
            ScheduleSeeder::class,
            PromoBannerSeeder::class,
        ]);
    }
}
