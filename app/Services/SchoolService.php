<?php

namespace App\Services;

use App\Http\Resources\Admin\UserResource;
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
                ->selectRaw("CONCAT(name, ' (', short_name, ')') AS label, id AS value, short_name")
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

    public static function getTeacherStudents($teacher_id): array
    {
        $cacheKey = 'user_dashboard:' . $teacher_id;
        $userQuery = User::query()
            ->whereHas('teachers', static function ($query) use ($teacher_id) {
                $query->where('teacher_id', $teacher_id);
            });
        $examTakenCount = Cache::remember($cacheKey, config('quiz.cache'), function () use ($userQuery) {
            return $userQuery->selectRaw('users.id, SUM((SELECT COUNT(*) FROM results WHERE results.user_id = users.id)) AS total_results')
                ->groupBy('users.id')
                ->pluck('total_results')
                ->sum();
        });
        $students = UserResource::collection(
            $userQuery->select('id', 'first_name', 'last_name', 'email', 'deleted_at')
                ->filter(request()->only('search', 'trashed', 'column', 'direction'))
                ->paginate(config('quiz.pagination'))
                ->withQueryString());


        $totalStudent = $students->total();

        return compact('totalStudent', 'examTakenCount', 'students');
    }
}
