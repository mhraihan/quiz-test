<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if the user is logged in
        if (!$user) {
            return redirect('/login');
        }

        if (!$user->isAdmin() && !$user->school_id) {
            return redirect()->route('user.profile');
        }
        if ($user->isStudent()) {
            $teacher = $user?->teachers()->first();
            if (!optional($teacher)->school_id) {
                return redirect()->route('user.profile');
            }
        }
        // Check if the user is a student and has a school_id and teacher_id

        return $next($request);
    }
}
