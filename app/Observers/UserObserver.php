<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserObserver
{

    public function saving(User $user): void
    {
        if (request()->has('password')){
            $user->password = Hash::make(request()->password );
        }
        $user->assignRole(request()->roles ?? 'student');
    }
}
