<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use Inertia\Response;
use Inertia\ResponseFactory;
use JetBrains\PhpStorm\NoReturn;
use QCod\ImageUp\Exceptions\InvalidUploadFieldException;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|ResponseFactory
     */
    public function index(): Response|ResponseFactory
    {
        return inertia('Question/Index', [
            'Questions' => Question::query()
                ->orderBy('created_at', 'DESC')
                ->filter(request()->only('search', 'trashed'))
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($question) => [
                    'id' => $question->id,
                    'title' => $question->title
                ]),
            'title' => 'All Questions'
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response|ResponseFactory
     */
    public function trash(): Response|ResponseFactory
    {
        return inertia('Question/Trash', [
            'Questions' => Question::query()
                ->orderBy('created_at', 'DESC')
                ->onlyTrashed()
                ->paginate(20)
                ->withQueryString()
                ->through(fn ($question) => [
                    'id' => $question->id,
                    'title' => $question->title
                ]),
            'title' => 'All Trashed Questions'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response|ResponseFactory
     */
    public function create(): Response|ResponseFactory
    {
        return inertia('Question/Create', [
            'Categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreQuestionRequest $request
     * @return RedirectResponse
     */
    public function store(StoreQuestionRequest $request): RedirectResponse
    {
        $request->user()->questions()->create($request->validated());
        return redirect()->route('admin.questions.index')->with('success', 'Question Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response|null
     */
    #[NoReturn] public function show(Question $question): ?\Illuminate\Http\Response
    {
        dd($question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return RedirectResponse|Response|ResponseFactory
     */
    public function edit(Question $question): Response|ResponseFactory|RedirectResponse
    {
        try {
            return inertia('Question/Edit', [
                'Categories' => Category::all(),
                'Question' => $question,
                'image' => $question->image ? $question->imageUrl() : null,
            ]);
        } catch (InvalidUploadFieldException $e) {
            return redirect()->back()->withErrors([
                'create' => 'ups, there was an error' . $e
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StoreQuestionRequest $request
     * @param Question $question
     * @return RedirectResponse
     */
    public function update(StoreQuestionRequest $request, Question $question): RedirectResponse
    {
        if (is_null($request->image) && !is_null($question->image)){
            $question->deleteFile($question->image);
        }
        $question->update($request->validated());
        return redirect()->back()->with('success', 'Question updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return RedirectResponse
     */
    public function destroy(Question $question): RedirectResponse
    {
        if ($question->deleted_at) {
            return $this->forceDelete($question);
        }
        $question->delete();
        return redirect()->back()->with('success', 'Question Moved to Trash Successfully');
    }

    public function restore(Question $question): RedirectResponse
    {
        $question->restore();
        return redirect()->back()->with('success', 'Question restored.');
    }

    public function forceDelete(Question $question): RedirectResponse
    {
        if (!is_null($question->image)){
            $question->deleteFile($question->image);
        }
        $question->forceDelete();
        return redirect()->route('admin.questions.trash')->with('success', 'Question Delete Successfully');
    }
}
