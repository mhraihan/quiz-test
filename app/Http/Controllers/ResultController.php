<?php

namespace App\Http\Controllers;

use App\Http\Requests\Result\StoreResultRequest;
use App\Traits\ResultTraits;
use Exception;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use App\Models\{Result, Question, User};

class ResultController extends Controller
{
    use ResultTraits;

    public function index(): Response
    {
        $user = auth()->user();
        return inertia('Result/Index', [
                'results' => fn() => $this->results($user),
                'exam' => fn() => $this->exam($user)->only(['results_sum_total_questions', 'results_sum_correct_answered']),
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
        $this->authorize('view', $result);
        ['questions' => $questions] = $result->getDataFromQuestions($result->questions_answered);
        return inertia('Result/Show', [
            'result' => $result,
            'questions' => $questions,
            'name' =>$result->user->name(),
        ]);
    }

}
