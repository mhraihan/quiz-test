<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Services\SchoolService;
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
            'Users' => UserResource::collection(
                User::query()
                    ->select('id', 'first_name', 'last_name', 'email', 'deleted_at')
                    ->filter(request()->only('search', 'trashed', 'column', 'direction'))
                    ->role('student')
                    ->paginate()
                    ->withQueryString())
            ,
            'title' => 'All Student',
            'filters' => request()->all('search', 'trashed', 'column', 'direction'),
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
        if (!($user->roles()->pluck('name')->first() === UserEnum::STUDENT->value)) {
            abort(403, "User must be a student");
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
