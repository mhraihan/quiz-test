<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Question;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use Inertia\Response;
use Inertia\ResponseFactory;
use QCod\ImageUp\Exceptions\InvalidUploadFieldException;

class QuestionController extends Controller
{

    public function index(): Response|ResponseFactory
    {
        $this->authorize('view');
        return inertia('Question/Index', [
            'Questions' => Question::query()->index(),
            'title' => 'All Questions'
        ]);
    }

    public function trash(): Response|ResponseFactory
    {
        $this->authorize('view');
        return inertia('Question/Trash', [
            'Questions' => Question::query()->index(true),
            'title' => 'All Trashed Questions'
        ]);
    }

    public function create(): Response|ResponseFactory
    {
        $this->authorize('create');
        return inertia('Question/Create', [
            'Categories' => Category::all(),
            'Topics' => Topic::all(),
        ]);
    }

    public function store(StoreQuestionRequest $request): RedirectResponse
    {
        $this->authorize('update');
        $request->user()->questions()->create($request->validated());
        return redirect()->route('admin.questions.index')->with('success', 'Question Created Successfully');
    }

    public function show(): void
    {
       abort(404);
    }

    public function edit(Question $question): Response|ResponseFactory|RedirectResponse
    {
        $this->authorize('view');
        try {
            return inertia('Question/Edit', [
                'Categories' => Category::all(),
                'Topics' => Topic::all(),
                'Question' => $question,
                'image' => $question->image ? $question->imageUrl() : null,
            ]);
        } catch (InvalidUploadFieldException $e) {
            return redirect()->back()->withErrors([
                'error' => 'ups, there was an error' . $e
            ]);
        }
    }

    public function update(StoreQuestionRequest $request, Question $question): RedirectResponse
    {
        $this->authorize('update');

        if (is_null($request->image) && !is_null($question->image)) {
            $question->deleteFile($question->image);
        }
        $question->update($request->validated());
        return redirect()->back()->with('success', 'Question updated.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $this->authorize('delete');

        if ($question->deleted_at) {
            return $this->forceDelete($question);
        }
        $question->delete();
        return redirect()->back()->with('success', 'Question Moved to Trash Successfully');
    }

    public function restore(Question $question): RedirectResponse
    {
        $this->authorize('update');

        $question->restore();
        return redirect()->back()->with('success', 'Question restored.');
    }

    public function forceDelete(Question $question): RedirectResponse
    {
        $this->authorize('delete');

        if (!is_null($question->image)) {
            $question->deleteFile($question->image);
        }
        $question->forceDelete();
        return redirect()->route('admin.questions.trash')->with('success', 'Question Delete Successfully');
    }
}
