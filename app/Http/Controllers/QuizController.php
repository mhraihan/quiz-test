<?php

namespace App\Http\Controllers;

use App\Models\{Topic, Category, Question};
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function index()
    {
        return inertia('Quiz/Index', [
            'Categories' => Category::query()->select('title as label', 'id as value')->get(),
            'Topics' => Topic::query()->select('title as label', 'id as value')->get()
        ]);
    }

    public function show(Request $request)
    {

        $request->validate([
            'category_id' => ['required', 'numeric','between:1,4'],
            'topic_id' => ['nullable', 'array'],
            'topic_id.*' => ['nullable', 'numeric'],
            'howManyQuestions' => ['required', 'numeric', 'between:1,20'],
        ]);

        $topics = array_filter(request()->input('topic_id'));

        $questions = Question::query()
            ->when($topics, function ($query, $topics) {
                $query->whereIn('topic_id', $topics);
            })
            ->where('category_id', request()->input('category_id'))
            ->inRandomOrder()
            ->limit($request->input('howManyQuestions'))
            ->select(['id', 'title', 'details','options', 'image'])
            ->get()
            ->shuffle()
            ->map(fn($quiz) => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'details' => $quiz->details,
                'options' => $quiz->options,
                'answer' => null,
                'image' => $quiz->image ? $quiz->imageUrl() : null
            ]);

        return response()->json(['questions' => $questions, 'start_time' => now()]);
    }
}
