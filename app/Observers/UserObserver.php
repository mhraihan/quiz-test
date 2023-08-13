<?php

namespace App\Observers;

use App\Enums\UserEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserObserver
{

    public function creating(User $user): void
    {
        if ($user->roles->isEmpty() || !request()->has('roles')) {
            $user->assignRole(request()->roles ?? UserEnum::STUDENT->value);
        }
    }
    public function saving(User $user): void
    {
        if (request()->has('password')) {
            $user->password = Hash::make(request()->password);
        }
    }
}
