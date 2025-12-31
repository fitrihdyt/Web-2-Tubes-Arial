<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Facility;

class HotelSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            ['name' => 'Hotel Indonesia Kempinski', 'city' => 'Jakarta', 'star' => 5],
            ['name' => 'The Ritz-Carlton Mega Kuningan', 'city' => 'Jakarta', 'star' => 5],
            ['name' => 'Hotel Mulia Senayan', 'city' => 'Jakarta', 'star' => 5],
            ['name' => 'Grand Hyatt Jakarta', 'city' => 'Jakarta', 'star' => 5],
            ['name' => 'Aston Braga Hotel', 'city' => 'Bandung', 'star' => 4],
            ['name' => 'The Trans Luxury Hotel', 'city' => 'Bandung', 'star' => 5],
            ['name' => 'Grand Tjokro Bandung', 'city' => 'Bandung', 'star' => 4],
            ['name' => 'Hotel Tentrem', 'city' => 'Yogyakarta', 'star' => 5],
            ['name' => 'Royal Ambarrukmo', 'city' => 'Yogyakarta', 'star' => 5],
            ['name' => 'The Phoenix Hotel', 'city' => 'Yogyakarta', 'star' => 5],
            ['name' => 'JW Marriott Surabaya', 'city' => 'Surabaya', 'star' => 5],
            ['name' => 'Hotel Majapahit', 'city' => 'Surabaya', 'star' => 5],
            ['name' => 'Aryaduta Bali', 'city' => 'Bali', 'star' => 5],
            ['name' => 'The Westin Resort Nusa Dua', 'city' => 'Bali', 'star' => 5],
            ['name' => 'Hard Rock Hotel Bali', 'city' => 'Bali', 'star' => 5],
            ['name' => 'Harris Hotel Kuta', 'city' => 'Bali', 'star' => 4],
            ['name' => 'Novotel Bogor Golf Resort', 'city' => 'Bogor', 'star' => 4],
            ['name' => 'Atria Hotel Malang', 'city' => 'Malang', 'star' => 4],
            ['name' => 'Swiss-Belhotel Solo', 'city' => 'Solo', 'star' => 4],
            ['name' => 'Grand Zuri Pekanbaru', 'city' => 'Pekanbaru', 'star' => 4],
        ];

        foreach ($hotels as $data) {

            $hotel = Hotel::create([
                'name'        => $data['name'],
                'description' => 'Hotel berbintang dengan fasilitas lengkap dan lokasi strategis.',
                'address'     => 'Jl. Raya Utama No. ' . rand(1, 200),
                'city'        => $data['city'],
                'star'        => $data['star'],
                'latitude'    => -6.2 + (rand(-200, 200) / 1000),
                'longitude'   => 106.8 + (rand(-200, 200) / 1000),
            ]);

            // attach 6–10 fasilitas
            $hotel->facilities()->attach(
                Facility::inRandomOrder()->limit(rand(6, 10))->pluck('id')
            );
        }
    }
}
