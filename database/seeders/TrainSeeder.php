<?php

namespace Database\Seeders;

use App\Models\Train;
use Illuminate\Database\Seeder;

class TrainSeeder extends Seeder
{
    public function run(): void
    {
        $trains = [
            ['name' => 'Argo Bromo Anggrek', 'train_number' => 'KA-001', 'class' => 'eksekutif', 'capacity' => 400],
            ['name' => 'Argo Wilis', 'train_number' => 'KA-002', 'class' => 'eksekutif', 'capacity' => 350],
            ['name' => 'Argo Lawu', 'train_number' => 'KA-003', 'class' => 'eksekutif', 'capacity' => 380],
            ['name' => 'Gajayana', 'train_number' => 'KA-004', 'class' => 'eksekutif', 'capacity' => 420],
            ['name' => 'Bangunkarta', 'train_number' => 'KA-005', 'class' => 'bisnis', 'capacity' => 500],
            ['name' => 'Mutiara Selatan', 'train_number' => 'KA-006', 'class' => 'bisnis', 'capacity' => 480],
            ['name' => 'Sancaka', 'train_number' => 'KA-007', 'class' => 'bisnis', 'capacity' => 450],
            ['name' => 'Penataran', 'train_number' => 'KA-008', 'class' => 'ekonomi', 'capacity' => 600],
            ['name' => 'Majapahit', 'train_number' => 'KA-009', 'class' => 'ekonomi', 'capacity' => 580],
            ['name' => 'Matarmaja', 'train_number' => 'KA-010', 'class' => 'ekonomi', 'capacity' => 620],
        ];

        foreach ($trains as $train) {
            Train::create(array_merge($train, ['is_active' => true]));
        }
    }
}
