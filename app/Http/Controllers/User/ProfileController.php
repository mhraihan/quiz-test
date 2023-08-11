<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\School;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Response;
use Inertia\ResponseFactory;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{

    public function index(Request $request): Response|ResponseFactory
    {
        $schools = cache()->remember('schools', now()->addHour(24), static function(){
            return School::query()
            ->latest('created_at')
            ->orderBy('name')
            ->selectRaw("CONCAT(name, ' (', short_name, ')') AS label, id AS value")
            ->limit(50)
            ->get();
        });

        $user = auth()->user();
        $current_school = $user?->school_id;
        $teacher_school_id = $request->school_id ?? $current_school;
        $teachers = [];
        $current_teacher = null;
        $how_many_students = 0;
        if ($user?->isTeacher()) {
            $how_many_students = $user?->students()->count();
        }

        if ($user?->isStudent()) {
            $teacher = $user?->teachers()->first();
            $current_teacher = optional($teacher)->school_id === $user?->school_id ? optional($teacher)->id : null;
            if ($request->teacher_id && $request->teacher_id !== $current_teacher) {
                $current_teacher = null;
            }
            $teachers = User::where('school_id', $teacher_school_id)
                ->role('teacher')
                ->orderBy('first_name')
                ->selectRaw("CONCAT(first_name, ' ', last_name) AS label, id AS value")
                ->pluck('label', 'value')
                ->take(50);
        }

        return inertia('Profile', [
            'User' => fn() => $user,
            'Schools' => fn() => $schools,
            'Teachers' => fn() => $teachers,
            'current_school' => $current_school,
            'current_teacher' => fn() => $current_teacher,
            'how_many_students' => $how_many_students
        ]);
    }


    public function updateSchool(Request $request): RedirectResponse
    {
        try {
            // Check if the authenticated user is a teacher and has associated students
            if (auth()->user()->isTeacher() && auth()->user()->students()->count()) {
                return redirect()->back()->with('error', 'Cannot update the school when students are associated.');
            }

            // Define validation rules for the request parameters
            $rules = [
                'school_id' => 'required|exists:schools,id',
                'teacher_id' => 'nullable|integer|exists:users,id'
            ];

            // If the authenticated user is a student, require teacher_id
            if (auth()->user()->isStudent()) {
                $rules['teacher_id'] = 'required|integer|exists:users,id';
            }

            // Validate the request data against the defined rules
            $validatedData = $request->validate($rules);

            // Update the school_id for the user
            $user = auth()->user();
            $user?->update(['school_id' => $validatedData['school_id']]);

            // If teacher_id is provided, update the user's associated teachers using sync
            if ($validatedData['teacher_id']) {
                $user?->teachers()->sync($validatedData['teacher_id']);
            }

            return redirect()->back()->with('success', 'Profile has been updated');
        } catch (ValidationException $e) {
            // Catch validation errors and return the first error message
            $errors = $e->errors();
            $firstErrorMessage = $errors['teacher_id'][0] ?? 'Validation error.';
            return redirect()->back()->with('error', $firstErrorMessage);
        } catch (Exception $e) {
            // Catch other exceptions and return the error message
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $user = auth()->user();
            $request->validate([
                'current_password' => ['required', static function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail(__('The current password is incorrect.'));
                    }
                }],
                'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);
            return redirect()->back()->with('success', 'Password has been updated');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $firstError = reset($errors); // Get the first element of the $errors array

            if (is_array($firstError) && count($firstError) > 0) {
                $firstErrorMessage = $firstError[0];
                return redirect()->back()->with('error', $firstErrorMessage);
            } else {
                return redirect()->back()->with('error', 'something went wrong');
            }
            return redirect()->back()->with('error', $errors);
        } catch (Exception $e) {
            // Catch other exceptions and return the error message
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function updateProfile(UpdateUserRequest $request): RedirectResponse
    {
        try {
            $authenticatedUser = auth()->user();

            // Make sure the authenticated user is the same as the one being updated
            if ($authenticatedUser->id === $request->id) {
                $data = $request->validated();

                // Remove the 'id' field from the data array to avoid updating it
                unset($data['id']);

                $authenticatedUser->update($data);
                return redirect()->back()->withSuccess('Profile has been updated');
            }

            return redirect()->back()->withError('Unauthorized');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


}
