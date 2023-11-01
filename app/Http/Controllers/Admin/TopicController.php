<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Topic\StoreTopicRequest;
use App\Http\Requests\Topic\UpdateTopicRequest;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

class TopicController extends Controller
{

    public function index(): Response|ResponseFactory
    {
        $topics = Topic::query()
            ->select('id', 'title', 'deleted_at')
            ->orderBy('title')
            ->filter(request()->only('search', 'trashed', 'column', 'direction'))
            ->withCount('questions') // Eager load the question count
            ->paginate(30)
            ->withQueryString()
            ->through(fn($topic) => [
                'id' => $topic->id,
                'title' => $topic->title,
                'count' => $topic->questions_count, // Access the eager-loaded count
                'deleted_at' => $topic->deleted_at
            ]);

        return inertia('Topic/Index', [
            'Topics' => $topics,
            'title' => 'All Topics',
            'filters' => request()->all('search', 'trashed', 'column', 'direction'),
        ]);
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('Topic/Create');
    }

    public function store(StoreTopicRequest $request): RedirectResponse
    {
        Topic::create($request->safe()->all());
        return redirect()->route('admin.topics.index')->with('success', 'Topic Created Successfully');
    }


    public function show(Topic $topic)
    {
        $questions = $topic->questions()->index(false,'asc');
        return inertia('Topic/Show', [
            'Topic' => $topic,
            'Questions' => $questions
        ]);
    }

    public function edit(Topic $topic): Response|ResponseFactory
    {
        return inertia('Topic/Edit', [
            'Topic' => $topic
        ]);
    }

    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        if ($topic->deleted_at) {
            $topic->restore();
            return redirect()->route('admin.topics.index')->with('success', "Topic restored Successfully");
        }
        $topic->update($request->safe()->all());
        return redirect()->back()->with('success', 'Topic Updated Successfully');
    }

    public function destroy(Topic $topic): RedirectResponse
    {
        if ($topic->deleted_at) {
            $topic->forceDelete();
            return redirect()->route('admin.topics.index')->with('success', "Topic permanently deleted Successfully");
        }
        $topic->delete();
        return redirect()->back()->with('success', "Topic deleted Successfully");
    }
}
