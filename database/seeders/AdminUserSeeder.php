<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SUPER ADMIN
        User::firstOrCreate(
            ['email' => 'bomin@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('qwerty1234'),
                'role' => 'super_admin',
            ]
        );

        // ADMIN HOTEL
        User::firstOrCreate(
            ['email' => 'telmin@gmail.com'],
            [
                'name' => 'Hotel Admin',
                'password' => Hash::make('qwerty1234'),
                'role' => 'admin_hotel',
            ]
        );
    }
}
