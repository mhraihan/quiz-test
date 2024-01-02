<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TeacherRedirectIfAuthenticated
{

    public function handle(Request $request, Closure $next, $guard = null)
    {
        $user = auth()->guard($guard)->user();

        if ($user && method_exists($user, 'getRedirectRoute')) {
            return redirect()->route($user->getRedirectRoute());
        }

        return $next($request);
    }

}
