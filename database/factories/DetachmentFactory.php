<?php

namespace Database\Factories;

use App\Models\Detachment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Detachment>
 */
class DetachmentFactory extends Factory
{
    protected $model = Detachment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' - '.fake()->city(),
            'category' => fake()->randomElement(['commercial', 'industrial', 'residential']),
            'status' => 'approved',
            'street' => fake()->streetAddress(),
            'city' => fake()->city(),
            'province' => fake()->state(),
            'zip_code' => fake()->postcode(),
            'phone_number' => fake()->phoneNumber(),
        ];
    }
}
