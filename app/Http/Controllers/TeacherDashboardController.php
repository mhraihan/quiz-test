<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use App\Services\SchoolService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\ResponseFactory;

class TeacherDashboardController extends Controller
{

    private function getData(): array
    {
        $data = [];
        if (!$data) {
            $data = SchoolService::getTeacherStudents(auth()->id());
            $data['loading'] = false;
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
