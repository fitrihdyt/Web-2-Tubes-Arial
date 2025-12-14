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
        //
        User::firstOrCreate(
            ['email' => 'bomin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('qwerty1234'),
                'role' => 'admin',
            ]
        );
    }
}
