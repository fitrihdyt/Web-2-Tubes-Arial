<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            'WiFi',
            'AC',
            'Kolam Renang',
            'Parkir Gratis',
            'Restoran',
            'Breakfast',
            'Gym',
            'Spa',
            'Lift',
            'Resepsionis 24 Jam',
            'Laundry',
            'Meeting Room',
            'TV',
            'Air Panas',
            'Smoking Area',
            'Non Smoking Room',
            'Shuttle Bandara',
            'Other',
        ];

        foreach ($facilities as $facility) {
            Facility::create([
                'name' => $facility
            ]);
        }
    }
}
