<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Result;
use App\Models\User;
use App\Observers\CategoryObserver;
use App\Observers\ResultObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class EloquentEventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Category::observe(CategoryObserver::class);
        Result::observe(ResultObserver::class);
    }
}
