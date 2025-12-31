<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Guest;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guests = Guest::all();

        $guests->each(function ($guest) {
            Room::factory(rand(1, 5))->create([
                'guest_id' => $guest->id,
            ]);
        });
    }
}
