<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Question\UpdateQuestionRequest;
use App\Models\Question;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use Illuminate\Support\Facades\File;
use Inertia\Response;
use Inertia\ResponseFactory;
use PHPUnit\Exception;
use QCod\ImageUp\Exceptions\InvalidUploadFieldException;
use App\Traits\CachesCategoriesAndTopics;

class QuestionController extends Controller
{

    use CachesCategoriesAndTopics;

    /**
     * @throws AuthorizationException
     */
    public function index(): Response|ResponseFactory
    {

        $this->authorize('view', Question::class);
        return inertia('Question/Index', [
            'Questions' => Question::query()->index(),
            'title' => 'All Questions'
        ]);
    }

    public function trash(): Response|ResponseFactory
    {
        $this->authorize('view', Question::class);
        return inertia('Question/Trash', [
            'Questions' => Question::query()->index(true),
            'title' => 'All Trashed Questions'
        ]);
    }

    /**
     * @throws AuthorizationException
     */
    public function create(): Response|ResponseFactory
    {
        $this->authorize('create', Question::class);
        return inertia('Question/Create', [
            'Categories' => $this->categoriesCache(),
            'Topics' => $this->topicsCache(),
        ]);
    }


    public function store(StoreQuestionRequest $request): RedirectResponse
    {
        try {
            $this->authorize('create', Question::class);
            $question = $request->user()->questions()->create($request->safe()->all());
            return redirect()->route('admin.questions.show', $question->id)->with('success', 'Question Created Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\JsonException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * @throws AuthorizationException
     */
    public function show(Question $question): ResponseFactory|Response
    {
        $this->authorize('view', Question::class);
        return inertia('Question/Show', [
            'Categories' => $this->categoriesCache(),
            'Topics' => $this->topicsCache(),
            'Question' => $question,
            'User' => $question->user->name(),
            'image' => $question->image ? $question->imageUrl() : null,
        ]);
    }

    public function edit(Question $question): Response|ResponseFactory|RedirectResponse
    {
        $this->authorize('view', Question::class);
        try {
            return inertia('Question/Edit', [
                'Categories' => $this->categoriesCache(),
                'Topics' => $this->topicsCache(),
                'Question' => $question,
                'image' => $question->image ? $question->imageUrl() : null,
            ]);
        } catch (InvalidUploadFieldException $e) {
            return redirect()->back()->withError([
                'error' => 'ups, there was an error' . $e
            ]);
        }
    }

    public function update(UpdateQuestionRequest $request, Question $question): RedirectResponse
    {
        $this->authorize('update', Question::class);

        if ($request->input('_duplicate')) {
            return $this->duplicateQuestion($question);
        }

        if (is_null($request->image) && !is_null($question->image)) {
            $question->deleteFile($question->image);
        }

        $question->update($request->safe()->all());

        return redirect()->back()->with('success', 'Question updated.');
    }

    public function destroy(Question $question): RedirectResponse
    {
        $this->authorize('delete', Question::class);

        if ($question->deleted_at) {
            return $this->forceDelete($question);
        }
        $question->delete();
        return redirect()->back()->with('success', 'Question Moved to Trash Successfully');
    }

    public function restore(Question $question): RedirectResponse
    {
        $this->authorize('update', Question::class);

        $question->restore();
        return redirect()->back()->with('success', 'Question restored.');
    }

    public function forceDelete(Question $question): RedirectResponse
    {
        $this->authorize('delete', Question::class);

        if (!is_null($question->image)) {
            $question->deleteFile($question->image);
        }
        $question->forceDelete();
        return redirect()->route('admin.questions.trash')->with('success', 'Question Delete Successfully');
    }

    public function duplicateQuestion(Question $question): RedirectResponse
    {
        try {
            $newQuestion = new Question($question->toArray());

            $newQuestion->id = null; // Set the ID to null to create a new record.
            $newQuestion->user_id = $question->user_id;
            $newQuestion->save();

            if (!empty($question->image)) {
                $sourceImagePath = storage_path("app/public/{$question->image}");
                $imageInfo = pathinfo($question->image);
                $newImageName = uniqid('', true) . "." . $imageInfo['extension'];
                $newImagePath = "questions/" . $newImageName;

                if (File::copy($sourceImagePath, storage_path("app/public/{$newImagePath}"))) {
                    // Update the image path in the duplicated question.
                    $newQuestion->image = $newImagePath;
                    $newQuestion->save();
                }
            }
            return redirect()->route('admin.questions.show', $newQuestion->id)->with('success', 'Duplicated Question Created Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\JsonException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
