<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\{Question};
use Illuminate\Http\Request;
use App\Traits\CachesCategoriesAndTopics;

class QuizController extends Controller
{

    use CachesCategoriesAndTopics;

    public function index()
    {
        $newCategory = ["label" => "Random", "value" => 0];
        $allCategory = $this->categoriesCache(true);
        array_splice($allCategory, 0, 0, [$newCategory]);

        return inertia('Quiz/Index', [
            'Categories' => $allCategory,
            'Topics' => $this->topicsCache(true)
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        try {
            $validLanguages = implode(',', array_column(config('quiz.languages'), 'value'));
            $request->validate([
                'language' => ['required', 'string', 'in:' . $validLanguages],
                'category_id' => ['required', 'numeric', 'between:0,4'],
                'topic_id' => ['nullable', 'array'],
                'topic_id.*' => ['nullable', 'numeric'],
                'howManyQuestions' => ['required', 'numeric', 'between:1,20'],
            ]);

            $topics = array_filter(request()->input('topic_id'));
            $selectedFields = ['id', 'title', 'details', 'options', 'image'];
            if ($request->input('language') === config('quiz.languages')[1]['value']) {
                $selectedFields = ['id', 'title_two as title', 'details_two as details', 'options_two as options', 'image'];
            }
            $questions = Question::query()
                ->when($topics, static function ($query, $topics) {
                    $query->whereIn('topic_id', $topics);
                })
                ->when(request()->input('category_id') > 0, static function ($query) {
                    $query->where('category_id', request()->input('category_id'));
                })
                ->inRandomOrder()
                ->limit($request->input('howManyQuestions'))
                ->select($selectedFields)
                ->get()
                ->shuffle()
                ->map(static fn($quiz) => [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'details' => $quiz->details,
                    'options' => $quiz->options,
                    'answer' => null,
                    'image' => $quiz->image ? $quiz->imageUrl() : null
                ]);

            return response()->json(['questions' => $questions, 'start_time' => now(), "language" => request()->language]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
