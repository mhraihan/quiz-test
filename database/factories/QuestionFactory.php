<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->sentence(),
            'details' => fake()->paragraphs(random_int(1,6),true),
            'explain' => fake()->paragraphs(random_int(0,6),true),
            'correct_answer' =>  fake()->randomElement(['a','b','c','d']),
            'category_id' => random_int(1,4),
            'user_id' => random_int(1,1),
            'options' => ["a" => fake()->word(), "b" => fake()->word(), "c" => fake()->word(),"d" => fake()->word()]
        ];
    }
}
