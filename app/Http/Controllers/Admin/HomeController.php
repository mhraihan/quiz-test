<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Models\Result;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Inertia\Inertia;

class HomeController extends Controller
{
    private function getUsersByRole($roleName): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::query()
                ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.deleted_at')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->whereIn('roles.name', [$roleName])
                ->whereNull('users.deleted_at')
                ->limit(config('quiz.limit'))
                ->latest('users.created_at')
                ->get()
        );
    }

    private function usersCount($roleName): int
    {
        return User::query()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->whereIn('roles.name', [$roleName])
            ->count();
    }

    private function getData(): array
    {
        $cacheKey = 'user_dashboard:' . auth()->id();

        $data = Cache::get($cacheKey);


        if (!$data) {
            // Data not found in cache, fetch and cache it
            $totalTeacher = $this->usersCount(UserEnum::TEACHER->value);
            $totalStudent = $this->usersCount(UserEnum::STUDENT->value);
            $examTakenCount = Result::query()->count();
            $totalSchools = School::query()->count();

            if (auth()->user()->isSuperAdmin()) {
                $superAdmin = $this->getUsersByRole(UserEnum::SUPER_ADMIN->value);
                $admin = $this->getUsersByRole(UserEnum::ADMIN->value);
            } else {
                $superAdmin = [];
                $admin = [];
            }
            $student = $this->getUsersByRole(UserEnum::STUDENT->value);
            $teacher = $this->getUsersByRole(UserEnum::TEACHER->value);

            // Store the data in cache
            $data = compact('superAdmin', 'admin', 'student', 'teacher', 'totalTeacher', 'totalStudent', 'examTakenCount', 'totalSchools');

            // Cache the data for a specific duration (e.g., 60 minutes)
            $data['loading'] = false;
            Cache::put($cacheKey, $data, now()->addMinutes(2));
        }


        return $data;
    }

    public function __invoke(Request $request): \Inertia\Response|\Inertia\ResponseFactory
    {
        return inertia('Home/Index', [
            'data' => Inertia::lazy(fn() => $this->getData())
        ]);
    }
}
