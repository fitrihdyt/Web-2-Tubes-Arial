<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hotel;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $roomTypes = [
            ['type' => 'Standard Room', 'price' => 450000],
            ['type' => 'Superior Room', 'price' => 650000],
            ['type' => 'Deluxe Room', 'price' => 900000],
            ['type' => 'Executive Room', 'price' => 1200000],
            ['type' => 'Suite Room', 'price' => 1800000],
        ];

        foreach (Hotel::all() as $hotel) {
            foreach ($roomTypes as $room) {
                Room::create([
                    'hotel_id' => $hotel->id,
                    'name'     => $room['type'],
                    'price'    => $room['price'] + rand(-50000, 150000),
                    'capacity' => rand(2, 4),
                    'stock'    => rand(5, 15),
                ]);
            }
        }
    }
}
