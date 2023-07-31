<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Result;
use App\Models\User;
use App\Services\ResultService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    protected $model = Result::class;

    public function definition()
    {
        $resultService = new ResultService();
        $questions = $this->generateRandomQuestions();
        [
            'correct_answered' => $correct_answered
        ] = $resultService->processResultData($questions);

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            "language" => collect(config('quiz.languages'))->pluck('value')->random(),
            'complete' => $this->faker->boolean(),
            'total_questions' => count($questions),
            'correct_answered' => $correct_answered,
            'start_time' => now(),
            'stop_time' => now()->addMinutes($this->faker->numberBetween(1, 140)),
            'questions_answered' => $questions,
        ];
    }

    private function generateRandomQuestions(): array
    {
        $questionsCount = $this->faker->numberBetween(10, 20);
        $answerOptions = ['a', 'b', 'c', 'd'];

        return Question::inRandomOrder()
            ->take($questionsCount)
            ->pluck('id')
            ->map(static fn($item) => [
                'id' => $item,
                'answer' => $answerOptions[array_rand($answerOptions)],
            ])
            ->all();
    }
}
