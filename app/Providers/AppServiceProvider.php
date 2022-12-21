<?php

namespace App\Providers;

use App\Enums\UserEnum;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Implicitly grant "Super-Admin" role all permission checks using can()
        Gate::after(function ($user, $ability) {
            if ($user->hasRole(UserEnum::SUPER_ADMIN->value)) {
                return true;
            }
        });
    }
}
