<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'short_name' => strtoupper($this->faker->unique()->randomLetter().$this->faker->unique()->randomLetter()),
        ];
    }
}
