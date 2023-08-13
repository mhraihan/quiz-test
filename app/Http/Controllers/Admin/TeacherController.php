<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Services\SchoolService;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class TeacherController extends Controller
{

    public function index(): Response|ResponseFactory
    {
        return inertia('User/Index', [
            'Users' => UserResource::collection(
                User::query()
                    ->select('id', 'first_name', 'last_name', 'email', 'deleted_at')
                    ->filter(request()->only('search', 'trashed', 'column', 'direction'))
                    ->role(UserEnum::TEACHER->value)
                    ->paginate()
                    ->withQueryString())
            ,
            'title' => 'All Teacher',
            'filters' => request()->all('search', 'trashed', 'column', 'direction'),
            'Role' => ucfirst(UserEnum::TEACHER->value),
        ]);
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('User/Create', [
            'Role' => UserEnum::TEACHER->value,
            'Schools' => fn() => SchoolService::getSchools(),
        ]);
    }

    public function show(User $user): Response|ResponseFactory
    {
        ray($user);
//        if (!($user->roles()->pluck('name')->first() === UserEnum::TEACHER->value)) {
//            abort(403, "User must be a Teachers");
//        }
        return inertia('User/Teacher', [
            'data' =>  SchoolService::getTeacherStudents($user->id),
            'Role' => UserEnum::TEACHER->value,
        ]);
    }

    public function edit(Request $request, User $user): Response|ResponseFactory
    {
        if (!($user->roles()->pluck('name')->first() === UserEnum::TEACHER->value)) {
            abort(403, "User must be a Teachers");
        }

        $current_school = $request?->school_id ?? $user?->school_id;
        $how_many_students = $user?->students()->count();


        return inertia('User/Edit', [
            'Role' => UserEnum::TEACHER->value,
            'User' => $user,
            'Schools' => fn() => SchoolService::getSchools(),
            'current_school' => (int)$current_school,
             'how_many_students' => $how_many_students
        ]);
    }
}
