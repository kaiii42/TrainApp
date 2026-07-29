<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@trainapp.com'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('password'),
                'phone'             => '081234567890',
                'role'              => 'admin',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // Mobile tester — admin role bypasses daily chat limit
        User::updateOrCreate(
            ['email' => 'tester@trainapp.com'],
            [
                'name'              => 'Tester Admin',
                'password'          => Hash::make('tester123'),
                'phone'             => '089999999999',
                'role'              => 'admin',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
