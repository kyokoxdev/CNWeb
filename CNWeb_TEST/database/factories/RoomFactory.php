<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guest_id' => \App\Models\Guest::factory(),
            'room_number' => $this->faker->unique()->bothify('Room-###'),
            'room_type' => $this->faker->randomElement(['Single', 'Double', 'Suite']),
            'price_per_night' => $this->faker->randomFloat(2, 50, 500),
            'check_in_date' => $this->faker->date(),
            'check_out_date' => $this->faker->optional()->date(),
            'status' => $this->faker->randomElement(['Available', 'Occupied', 'Maintenance']),
        ];
    }
}
