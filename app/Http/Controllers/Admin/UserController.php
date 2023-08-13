<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $property = UserEnum::from($request->roles)->getUserProperty();
        $user = User::create($request->safe()->all());
        $user?->teachers()->sync($request->input('teacher_id'));
        return redirect()->route($property['url'])->with('success', $property['store']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $property = UserEnum::from($request->roles)->getUserProperty();
        $user->update($request->safe()->all());
        $user?->teachers()->sync($request->input('teacher_id'));
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
//        $this->authorize('update');
        $property = UserEnum::from($user->roles()->pluck('name')->first())->getUserProperty();
        $user->restore();
        return redirect()->back()->with('success', $property['restore']);
    }
}
