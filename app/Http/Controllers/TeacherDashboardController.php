<?php

namespace App\Http\Controllers;

use App\Enums\UserEnum;
use App\Http\Resources\Admin\UserResource;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\ResponseFactory;

class TeacherDashboardController extends Controller
{

    private function getData(): array
    {
        $cacheKey = 'user_dashboard:' . auth()->id();

//        $data = Cache::get($cacheKey);
        $data = [];

        if (!$data) {
            $teacher_id = auth()->user()->id;
            $userQuery = User::query()
                ->whereHas('teachers', function ($query) use ($teacher_id) {
                    $query->where('teacher_id', $teacher_id);
                });
            $examTakenCount = $userQuery->selectRaw('users.id, SUM((SELECT COUNT(*) FROM results WHERE results.user_id = users.id)) AS total_results')
                ->groupBy('users.id')
                ->pluck('total_results')
                ->sum();
            $students = UserResource::collection(
                $userQuery->select('id', 'first_name', 'last_name', 'email', 'deleted_at')
                    ->filter(request()->only('search', 'trashed', 'column', 'direction'))
                    ->paginate(config('quiz.pagination'))
                    ->withQueryString());


            $totalStudent = $students->total();

            // Store the data in cache
            $data = compact('totalStudent', 'examTakenCount', 'students');

            // Cache the data for a specific duration (e.g., 60 minutes)
            $data['loading'] = false;
            Cache::put($cacheKey, $data, now()->addMinute(5));
        }


        return $data;
    }

    public function __invoke(Request $request): Response|ResponseFactory
    {
        return inertia('Teacher/Index', [
            'filters' => request()->all('search', 'trashed', 'column', 'direction'),
            'data' => Inertia::lazy(fn() => $this->getData())
        ]);
    }
}
