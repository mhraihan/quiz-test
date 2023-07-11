<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Traits\ResultTraits;
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
                    ->filter(request()->only('search', 'trashed','column', 'direction'))
                    ->role('student')
                    ->paginate()
                    ->withQueryString())
            ,
            'title' => 'All Student',
            'filters' => request()->all('search', 'trashed','column', 'direction'),
        ]);
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('User/Create', [
            'Role' => 'Student',
        ]);
    }

    public function show(User $user): Response|ResponseFactory
    {
        return inertia('User/Student', [
            'User' => fn() => $this->exam($user),
            'results' => fn() => $this->results($user),
            'Role' => 'Student',
        ]);
    }

    public function edit(User $user): Response|ResponseFactory
    {
        return inertia('User/Edit', [
            'Role' => 'Student',
            'User' => $user,
        ]);
    }
}
