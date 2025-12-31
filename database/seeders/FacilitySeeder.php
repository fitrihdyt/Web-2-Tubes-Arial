<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $facilities = [
            'Kolam Renang',
            'Gym',
            'WiFi Gratis',
            'Parkir Gratis',
            'Restoran',
            'Spa',
            'Resepsionis 24 Jam',
            'Lift',
            'AC',
        ];

        foreach ($facilities as $f) {
            Facility::firstOrCreate(['name' => $f]);
        }

        Facility::firstOrCreate(['name' => 'Other']);
    }
}
