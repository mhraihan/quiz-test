<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SchoolService;
use App\Services\UserService;
use App\Traits\ResultTraits;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class StudentController extends Controller
{
    use ResultTraits;

    public function index(): Response|ResponseFactory
    {
        return inertia('User/Index', [
            'Users' => UserService::getUsersByRole(),
            'title' => 'All Student',
            'filters' => request()->all('search', 'trashed', 'column', 'direction'),
            'Role' => ucfirst(UserEnum::STUDENT->value),
        ]);
    }

    public function create(Request $request): Response|ResponseFactory
    {
        $current_school = $request?->school_id ?? null;
        return inertia('User/Create', [
            'Role' => UserEnum::STUDENT->value,
            'Schools' => fn() => SchoolService::getSchools(),
            'Teachers' => fn() => SchoolService::getTeachers($current_school),

        ]);
    }

    public function show(User $user): Response|ResponseFactory
    {
        $authUser = auth()->user();
        $teacherId = auth()->id();

        if (!$authUser || !($authUser->isAdmin() || $authUser->isTeacher())) {
            abort(403);
        }

        if ($user->roles()->pluck('name')->first() !== UserEnum::STUDENT->value) {
            abort(403, "User must be a student");
        }

        if ($authUser->isTeacher() && !$user->belongsToTeacher($teacherId)) {
            abort(403, "Student does not belong to the given teacher");
        }

        return inertia('User/Student', [
            'User' => fn() => $this->exam($user),
            'results' => fn() => $this->results($user),
            'Role' => UserEnum::STUDENT->value,
        ]);
    }


    public function edit(Request $request, User $user): Response|ResponseFactory
    {
        if (!($user->roles()->pluck('name')->first() === UserEnum::STUDENT->value)) {
            abort(403, "User must be a student");
        }

        $current_school = $request?->school_id ?? $user?->school_id;
        $teacher = $user?->teachers()->first();
        $current_teacher = $teacher?->school_id === $user?->school_id ? $teacher?->id : null;

        $user['current_teacher'] = $current_teacher;

        return inertia('User/Edit', [
            'Role' => UserEnum::STUDENT->value,
            'User' => $user,
            'Schools' => fn() => SchoolService::getSchools(),
            'Teachers' => fn() => SchoolService::getTeachers($current_school),
            'current_school' => (int)$current_school,
            'current_teacher' => fn() => $current_teacher,
        ]);
    }
}
