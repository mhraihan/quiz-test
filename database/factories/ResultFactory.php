<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Result;
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
        $result = new Result();
        $questions = random_int(10,20);
        $answer = ['a','b','c','d'];
        $questions_answered =  Question::take($questions)->pluck('id')->map(fn($item, $key) => ['id' => $item, "answer" => $answer[random_int(0,3)]])->all();
        [
            'correct_answered' => $correct_answered
        ] = $result->getDataFromQuestions($questions_answered);
        return [
            'user_id' => random_int(1,4),
            'complete' => random_int(0, 1),
            'total_questions' => $questions,
            'correct_answered' => $correct_answered,
            'start_time' => now(),
            'stop_time' => now()->addMinutes(random_int(1,140)),
            'questions_answered' => $questions_answered,
        ];
    }
}
