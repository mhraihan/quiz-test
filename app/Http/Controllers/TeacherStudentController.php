<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResultTraits;

class TeacherStudentController extends Controller
{
    use ResultTraits;

    public function index()
    {
        $teacher_id = auth()->user()->id;
        return inertia('User/Index', [
            'Users' => UserResource::collection(
                User::query()
                    ->whereHas('teachers', function ($query) use ($teacher_id) {
                        $query->where('teacher_id', $teacher_id);
                    })
                    ->select('id', 'first_name', 'last_name', 'email', 'deleted_at')
                    ->filter(request()->only('search', 'trashed', 'column', 'direction'))
                    ->role('student')
                    ->paginate()
                    ->withQueryString())
            ,
            'title' => 'All Student',
            'filters' => request()->all('search', 'trashed', 'column', 'direction'),
        ]);
    }

    public function profile(User $user)
    {
        $teacherId = auth()->user()->id;
        if (!$user->belongsToTeacher($teacherId)) {
            abort(403);
        }
        return inertia('User/Student', [
            'User' => fn() => $this->exam($user),
            'results' => fn() => $this->results($user),
        ]);
    }
}
