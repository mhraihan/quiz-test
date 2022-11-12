<?php

namespace App\Http\Controllers;

use App\Models\{Result, Question};
use Illuminate\Http\Request;

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        $ids = array_column( $request->input('questions_answered'),'id');
        $answers = array_column( $request->input('questions_answered'),'answer');

        $questions = Question::query()->whereIn('id',$ids);
        $answered = $questions->pluck('correct_answer')->map(fn($item, $key): bool => ( $item == $answers[$key]));
        $correct_answer = count($answered->filter());
        $score = ceil(( $correct_answer / count($answers) ) * 100);

        $result = $request->user()->results()->create([
            'class_id' => $request->input('class_id'),
            'complete' => $request->input('complete'),
            'questions_answered' => $request->input('questions_answered'),
            'total_questions' => $request->input('total_questions'),
            'start_time' => $request->input('start_time'),
            'stop_time' => $request->input('stop_time'),
            'correct_answered' => $correct_answer,
            'score' => $score,
        ]);

        ray($result);
        return response()->json(['result' => $result]);
//        return redirect()->route('result.show',$result->id )->with('success','Exam Completed');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function show(Result $result)
    {
        return inertia('Result/Index',[
            'result' => $result
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function edit(Result $result)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Result $result)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $result)
    {
        //
    }
}
