<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Detachment>
 */
class DetachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Example: "Laguna Division" or "Batangas Command"
            'name' => fake()->city().' '.fake()->randomElement(['Division', 'Command', 'Detachment']),

            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'province' => fake()->city(),
            'zip_code' => fake()->randomNumber(),

            // Assign a assigned_officer by picking a random user's ID
            // Make sure you have users in your 'users' table!
            'phone_number' => fake()->phoneNumber(),

            // We do NOT set 'users_count' or 'personnel' here.
            // That value should be calculated by an observer or withCount(),
            // so we let the database handle it.
        ];
    }
}
