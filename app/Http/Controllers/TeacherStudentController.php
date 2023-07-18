<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Traits\ResultTraits;

class TeacherStudentController extends Controller
{
    use ResultTraits;

    public function userResults(User $user)
    {
        return cache()->remember('profile:' . $user->id, config('quiz.cache'), function () use ($user) {
            return [
                'User' => $this->exam($user),
                'results' => $this->results($user),
            ];
        });
    }

    public function index()
    {
        $teacher_id = auth()->user()->id;
        $cacheKey = 'users:' . $teacher_id . ':' . serialize(request()->all());
        $users = cache()->remember($cacheKey, 60, function () use ($teacher_id) {
            return User::query()
                ->whereHas('teachers', function ($query) use ($teacher_id) {
                    $query->where('teacher_id', $teacher_id);
                })
                ->select('id', 'first_name', 'last_name', 'email', 'deleted_at')
                ->filter(request()->only('search', 'trashed', 'column', 'direction'))
                ->role('student')
                ->paginate()
                ->withQueryString();
        });
        return inertia('User/Index', [
            'Users' => UserResource::collection($users),
            'title' => 'All Student',
            'filters' => request()->all('search', 'trashed', 'column', 'direction'),
        ]);
    }

    public function profile(User $user)
    {
        $teacherId = auth()->user()->id;
        if (!$user->belongsToTeacher($teacherId)) {
            abort(403);
        }
        return inertia('User/Student', $this->userResults($user));
    }
}
