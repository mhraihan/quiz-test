<?php

namespace App\Observers;

use App\Models\Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ResultObserver
{

    public function created(Result $result): void
    {
        $user_id = $result->user_id;
        if ($user_id) {
            DB::table('users')->where('id', $user_id)->update(['recent_exam' => now()]);

            $numberOfPages = 10; // Modify this to the maximum number of pages you expect

            // Clear cache for each page associated with the user's results
            for ($pageNumber = 1; $pageNumber <= $numberOfPages; $pageNumber++) {
                $cacheKey = 'users:' . $user_id . '-results:' . md5('page=' . $pageNumber);
                Cache::forget($cacheKey);
            }

            // Clear cache for exam
             Cache::forget($user_id . '-exam');
        }
    }

}
