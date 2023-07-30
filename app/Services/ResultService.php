<?php
// ResultService.php
namespace App\Services;
use App\Models\Question;

class ResultService
{
    public function processResultData($questionsAnswered): array
    {
        $questionsData = $this->getDataFromQuestions($questionsAnswered);
        $correctAnswered = count(array_filter($questionsData['answered']));
        return [
            'questions' => $questionsData['questions'],
            'answered' => $questionsData['answered'],
            'correct_answered' => $correctAnswered,
        ];
    }

    public function getDataFromQuestions(array $query): array
    {
        $ids = array_column($query, 'id');
        $answers = array_column($query, 'answer');
        $questions = Question::query()
            ->whereIn('id', $ids)
            ->select('title', 'details', 'correct_answer', 'explain', 'options')
            ->orderByRaw("FIELD(id, " . implode(',', $ids) . ")")
            ->get();

        $answered = collect($questions)->pluck('correct_answer')->map(static function ($item, $key) use ($answers, $questions) {
            $answer = $item === $answers[$key];
            $questions[$key]['answer'] = $answers[$key];
            return $answer;
        });

        return [
            'questions' => $questions,
            'answered' => $answered->all(),
        ];
    }
}
