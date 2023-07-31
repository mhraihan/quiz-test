<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use FakerChineseLorem\Provider\zh_CN\Lorem as ChineseLorem;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */

    public function definition()
    {
        $faker = Faker::create();
        $faker->addProvider(new ChineseLorem($faker));
        return [
            'title' => fake()->sentence(),
            'title_two' => $faker->sentence(),
            'details' => fake()->paragraphs(random_int(1,6),true),
            'details_two' => $faker->paragraphs(random_int(1,6),true),
            'explain' => fake()->paragraphs(random_int(0,6),true),
            'explain_two' => $faker->paragraphs(random_int(0,6),true),
            'correct_answer' =>  fake()->randomElement(['a','b','c','d']),
            'category_id' => random_int(1,4),
            'user_id' => random_int(1,1),
            'topic_id' => random_int(1,3),
            'options' => ["a" => fake()->word(), "b" => fake()->word(), "c" => fake()->word(),"d" => fake()->word()],
            'options_two' => ["a" => $faker->word(), "b" => $faker->word(), "c" => $faker->word(),"d" => $faker->word()]
        ];
    }
}
