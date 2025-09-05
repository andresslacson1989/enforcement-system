<?php

namespace Database\Factories;

use App\Models\Detachment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $first_name = $this->faker->firstName();
        $middle_name = $this->faker->lastName();
        $last_name = $this->faker->lastName();
        $name = $first_name.' '.$middle_name.' '.$last_name;

        return [
            'name' => $name,
            'first_name' => $first_name,
            'middle_name' => $middle_name,
            'last_name' => $last_name,
            'gender' => 'male',
            'employee_number' => fake()->unique()->numerify('EMP-######'),
            'street' => fake()->streetName(),
            'city' => fake()->city(),
            'province' => fake()->city(),
            'zip_code' => fake()->postcode(),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('123456'),
            'status' => 'hired',
            'sss_number' => fake()->numerify('##-#######-#'),
            'philhealth_number' => fake()->numerify('####-####-####'),
            'pagibig_number' => fake()->numerify('####-####-####'),
            'birthdate' => fake()->dateTimeBetween('-50 years', '-20 years'),
            'license_number' => fake()->numerify('###-###-####'),
            'remember_token' => Str::random(10),
            // 'detachment_id' => Detachment::inRandomOrder()->first()->id,

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
