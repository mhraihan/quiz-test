<?php

namespace App\Http\Controllers;

use App\Http\Requests\Result\StoreResultRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Inertia\Response;
use App\Models\{Result, Question};

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        ['questions' => $questions ] = $result->getDataFromQuestions($result->questions_answered);
        return inertia('Result/Show', [
            'result' => $result,
            'questions' => $questions
        ]);
    }

}
