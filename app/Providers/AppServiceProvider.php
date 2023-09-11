<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intonate\TinkerZero\TinkerZeroServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (class_exists(TinkerZeroServiceProvider::class)) {
            $this->app->register(TinkerZeroServiceProvider::class);
        }
    }
}
