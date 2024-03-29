<?php

namespace Database\Factories;

use App\Models\School;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $birthday = fake()->dateTimeInInterval('-30 years ago', '-30 years + 905 days');
        $formattedBirthday = Carbon::instance($birthday)->format('Y-m-d');
        return [
            'school_id' => School::inRandomOrder()->first()->id, // Assign a random school_id
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'gender' =>fake()->randomElement(['male', 'female']),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'birthday' => $formattedBirthday,
            'country' => fake()->randomElement(['HK', 'CN']),
            'state' => fake()->state(),
            'city' => fake()->city(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'postcode' => fake()->postcode(),
            'active' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
