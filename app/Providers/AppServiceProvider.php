<?php

namespace App\Providers;

use Illuminate\Console\Application as Artisan;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Intonate\TinkerZero\TinkerZeroServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The application instance.
     *
     * @var \LaravelZero\Framework\Application
     */
    protected $app;

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->prepareDatabaseDirectory();
        $this->prepareCompiledViewCacheDirectory();

        if ($this->app->environment('production')) {
            $this->migrateDatabase();
        }
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

    private function prepareDatabaseDirectory(): void
    {
        $this->prepareDirectory(File::dirname(config('database.connections.sqlite.database')));
    }

    private function prepareCompiledViewCacheDirectory(): void
    {
        $this->prepareDirectory(config('view.compiled'));
    }

    private function prepareDirectory(string $path): void
    {
        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    private function migrateDatabase(): void
    {
        Artisan::starting(function (Artisan $artisan) {
            $artisan->call(MigrateCommand::class, ['--force' => true]);
        });
    }
}
