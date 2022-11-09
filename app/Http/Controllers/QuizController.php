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
//        ray($request->all());
        $request->validate([
            'category_id' => ['required', 'numeric', 'exists:categories,id', 'between:1,4'],
            'topic_id' => ['nullable', 'array'],
            'topic_id.*' => ['nullable', 'numeric', 'exists:topics,id'],
            'howManyQuestions' => ['required', 'numeric', 'between:1,20'],
        ]);

        $topics = array_filter(request()->input('topic_id'));

        $questions = Question::query()
            ->with(['topic', 'category'])
            ->when($topics, function ($query, $topics) {
                ray($topics);

                $query->whereIn('topic_id', $topics);
            })
            ->where('category_id', request()->input('category_id'))
//            ->select('id', 'title', 'details', 'options')
            ->get()
            ->map(fn($quiz) => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'details' => $quiz->details,
                'options' => $quiz->options,
                'answer' => null
            ])
            ->shuffle()
            ->take($request->input('howManyQuestions'));

        return response()->json(['questions' => $questions]);
    }
}
