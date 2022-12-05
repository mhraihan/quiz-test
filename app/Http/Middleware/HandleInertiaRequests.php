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
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request)
    {
        $roles = $request->user()?->loadMissing('roles.permissions')->roles;

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => [
                    'id' => $request->user()->id ?? null,
                    'name' => $request->user()->name ?? null,
                    'email' => $request->user()->email ?? null,
                ],
                'roles' => $roles?->pluck('name')->first(),
                'can' => $roles?->flatMap(fn($role) => $role->permissions)->map(fn($permission) => [$permission['name'] => $request->user()->can($permission['name'])])->collapse()->all(),
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
