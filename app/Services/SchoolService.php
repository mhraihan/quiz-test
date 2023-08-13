<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
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

    public static function getTeachers($school_id)
    {
        return Cache::remember('teachers' . $school_id, config('quiz.cache'), static function () use ($school_id) {
            return User::where('school_id', $school_id)
                ->role('teacher')
                ->orderBy('first_name')
                ->selectRaw("CONCAT(first_name, ' ', last_name) AS label, id AS value")
                ->pluck('label', 'value')
                ->take(50);

        });
    }
}
