<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RoleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    private function getCachedRole()
    {
        $cacheKey = 'user_role:' . $this->id;

        return cache()->remember($cacheKey, now()->addMinutes(60), function () {
            return $this->roles()->pluck('name')->first();
        });
    }

    public function register()
    {
        $this->app->singleton('userRole', function ($app) {
            $cacheKey = 'user_role:' . $app->make('auth')->id();

            return cache()->remember($cacheKey, now()->addMinutes(60), function () use ($app) {
                $user = $app->make('auth')->user();
                if ($user) {
                    return $user->roles()->pluck('name')->first();
                }
                return null;
            });
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
