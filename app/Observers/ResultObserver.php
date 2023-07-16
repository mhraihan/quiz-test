<?php

namespace App\Observers;

use App\Models\Result;
use Illuminate\Support\Facades\DB;

class ResultObserver
{

    public function created(Result $result): void
    {
        $user_id = $result->user_id;
        if ($user_id) {
            DB::table('users')->where('id', $user_id)->update(['recent_exam' => now()]);
        }
    }

}
