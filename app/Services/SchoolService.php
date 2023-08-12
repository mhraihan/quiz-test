<?php
namespace App\Services;

use App\Models\School;
use Illuminate\Support\Facades\Cache;

class SchoolService
{
    public static function getSchools()
    {
        return Cache::remember('schools', now()->addHour(24), static function () {
            return School::query()
                ->latest('created_at')
                ->orderBy('name')
                ->selectRaw("CONCAT(name, ' (', short_name, ')') AS label, id AS value")
                ->limit(50)
                ->get();
        });
    }
}
