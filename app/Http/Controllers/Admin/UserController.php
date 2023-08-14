<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Inertia\ResponseFactory;

class UserController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        return inertia('User/Index', [
            'Users' => UserService::getUsersByRole(UserEnum::ADMIN),
            'title' => 'All Admin',
            'filters' => request()->all('search', 'trashed', 'column', 'direction'),
            'Role' => ucfirst(UserEnum::ADMIN->value),
        ]);
    }

    public function create(): Response|ResponseFactory
    {
        return inertia('User/Create', [
            'Role' => UserEnum::ADMIN->value,
        ]);
    }


    public function store(StoreUserRequest $request): RedirectResponse
    {
        $property = UserEnum::from($request->roles)->getUserProperty();
        $user = User::create($request->safe()->all());
        if ($request?->has("teacher_id")) {
            $user?->teachers()->sync($request->input('teacher_id'));
        }
        return redirect()->route($property['url'])->with('success', $property['store']);
    }

    public function edit(User $user): Response|ResponseFactory
    {
        if (!($user->roles()->pluck('name')->first() === UserEnum::ADMIN->value)) {
            abort(403, "User must be a Administrator");
        }
        return inertia('User/Edit', [
            'Role' => UserEnum::ADMIN->value,
            'User' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $property = UserEnum::from($request->roles)->getUserProperty();
        $user->update($request->safe()->all());
        if ($request?->has("teacher_id")) {
            $user?->teachers()->sync($request->input('teacher_id'));
        }
        return redirect()->back()->withSuccess($property['update']);
    }


    public function destroy(User $user): RedirectResponse
    {
        $property = UserEnum::from($user->roles()->pluck('name')->first())->getUserProperty();
        if ($user->deleted_at) {
            $user->forceDelete();
            return redirect()->route($property['url'], request('query'))->with('success', $property['delete']);
        }
        $user->delete();
        return redirect()->back()->with('success', $property['trash']);
    }

    public function restore(User $user): RedirectResponse
    {
        $property = UserEnum::from($user->roles()->pluck('name')->first())->getUserProperty();
        $user->restore();
        return redirect()->back()->with('success', $property['restore']);
    }
}
