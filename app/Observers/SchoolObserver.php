<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class SchoolObserver
{

    public function saving()
    {
        Cache::delete('schools');
    }

    public function deleting()
    {
        Cache::delete('schools');
    }

}
