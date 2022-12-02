<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => [
                    'id' => $request->user()->id ?? null,
                    'name' => $request->user()->name ?? null,
                    'email' => $request->user()->email ?? null,
                ],
//                'can'  => $request->user()?->loadMissing('roles.permissions')
//                    ->roles->flatMap(function ($role) {
//                        return $role->permissions;
//                    })->map(function ($permission) {
//                        return [$permission['title'] => auth()->user()->can($permission['title'])];
//                    })->collapse()->all(),
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => function () use ($request) {
                return [
                    'message' => $request->session()->get('message'),
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
        ]);
    }
}
