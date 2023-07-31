<?php

namespace App\Http\Controllers;

use App\Http\Requests\Result\StoreResultRequest;
use App\Traits\ResultTraits;
use Exception;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use App\Models\{Result};
use App\Services\ResultService;

class ResultController extends Controller
{
    use ResultTraits;

    public function index(): Response
    {
        $user = auth()->user();
        $pageNumber = 'page=' . request()->input('page', 1);
        $cacheKey = 'users:' . $user->id . '-results:' . md5($pageNumber);
        $userResults = cache()->remember($cacheKey, config('quiz.cache'), fn() => $this->results($user));
        $userExam = cache()->remember($user->id . '-exam', config('quiz.cache'), fn() => $this->exam($user)->only(['results_sum_total_questions', 'results_sum_correct_answered']));

        return inertia('Result/Index', [
                'results' => static fn() => $userResults,
                'exam' => static fn() => $userExam,
                'loading' => false,
            ]
        );
    }

    public function store(StoreResultRequest $request, ResultService $resultService): JsonResponse
    {
        try {
            ray($request->safe()->all());
            $resultData = $resultService->processResultData($request->input('questions_answered'));
            ray($request->safe()->all() + $resultData);
            $result = $request->user()->results()->create($request->safe()->all() + $resultData);
            return response()->json(compact('result'), 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(Result $result, ResultService $resultService): Response
    {
        try {
            ray($result);
            $this->authorize('view', $result);
            ['questions' => $questions] = $resultService->getDataFromQuestions($result->questions_answered,$result->language);
            return inertia('Result/Show', [
                'result' => $result,
                'questions' => $questions,
                'name' => $result->user->name(),
            ]);
        } catch (Exception $e) {
            abort(403);
        }
    }

}
