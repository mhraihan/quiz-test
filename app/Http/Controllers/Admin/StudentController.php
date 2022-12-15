<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Inertia\Response;
use Inertia\ResponseFactory;

class StudentController extends Controller
{

    public function index(): Response|ResponseFactory
    {
        return inertia('User/Index', [
            'Users' => UserResource::collection(
                User::query()
                    ->latest()
                    ->role('student')
                    ->when(request()->input('search'), function ($query, $search) {
                        $query->role('student')->where('first_name', 'LIKE', "%$search%");
                        $query->role('student')->orWhere('last_name', 'LIKE', "%$search%");
                        $query->role('student')->orWhere('email', 'LIKE', "%$search%");
                    })
                    ->paginate()
                    ->withQueryString())
            ,
            'title' => 'All Student'
        ]);
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('User/Create', [
            'Role' => 'student',
        ]);
    }

    public function show(User $user): Response|ResponseFactory
    {
        return inertia('User/Edit', [
            'Role' => 'student',
            'User' => $user,
        ]);
    }

    public function edit(User $user): Response|ResponseFactory
    {
        return inertia('User/Edit', [
            'User' => $user,
            'Role' => 'student',
        ]);
    }
}
