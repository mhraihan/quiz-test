<?php

namespace App\Observers;

use App\Models\Topic;
use Illuminate\Support\Facades\Cache;

class TopicObserver
{

    public function saving(Topic $topic)
    {
        Cache::delete('topics');
    }

    public function deleting(Topic $topic)
    {
        Cache::delete('topics');
    }

}
