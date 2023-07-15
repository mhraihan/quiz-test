<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResultPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Result $result)
    {
        if (!$user->can('check result')) {
            return false;
        }
        return $user->isSuperAdmin() || $user->isAdmin() || $user->id === $result->user_id || $result->user->belongsToTeacher($user->id);
    }


}
