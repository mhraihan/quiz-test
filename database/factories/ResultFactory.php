<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function definition()
    {
        $questions = random_int(10,20);
        $answer = ['a','b','c','d'];

        return [
            'total_questions' => $questions,
            'correct_answered' =>random_int(0,$questions),
            'start_time' => now(),
            'stop_time' => now()->addMinutes(random_int(1,10)),
            'questions_answered' =>Question::take($questions)->pluck('id')->map(fn($item, $key) => ['id' => $item, "answer" => $answer[random_int(0,3)]])->all(),
        ];
    }
}
