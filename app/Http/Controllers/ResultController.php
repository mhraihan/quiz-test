<?php

namespace App\Http\Controllers;

use App\Http\Requests\Result\StoreResultRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use App\Models\{Result, Question, User};

class ResultController extends Controller
{
    public function index(): Response
    {
        $query = auth()->user()->loadSum('results', 'total_questions')->loadSum('results', 'correct_answered');
        $result = $query->results()
            ->latest()
            ->select('id', 'complete', 'correct_answered', 'total_questions', 'stop_time', 'start_time', 'created_at')
            ->paginate(12)
            ->withQueryString()
            ->through(fn($result) => [
                'id' => $result->id,
                'complete' => $result->complete,
                'total_questions' => $result->total_questions,
                'score' => $result->score,
                'exam' => $result->exam['how_long'],
            ]);
        return inertia('Result/Index', [
                'results' => $result,
                'total_exam' => 0,
                'total_questions' => (int) $query->results_sum_total_questions,
                'score' => (float) number_format(($query->results_sum_correct_answered / $query->results_sum_total_questions) * 100,2)
            ]
        );
    }

    public function store(StoreResultRequest $request): JsonResponse
    {
        try {
            $result = new Result();
            [
                'correct_answered' => $correct_answered
            ] = $result->getDataFromQuestions($request->input('questions_answered'));
            $result = $request->user()->results()->create(
                $request->validated() + [
                    'correct_answered' => $correct_answered
                ]);
            ray($result);
            return response()->json(['result' => $result], 201);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Result $result): Response
    {
        ['questions' => $questions] = $result->getDataFromQuestions($result->questions_answered);
        return inertia('Result/Show', [
            'result' => $result,
            'questions' => $questions
        ]);
    }

}
