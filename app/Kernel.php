<?php

namespace App;

use LaravelZero\Framework\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    const APP_NAME = 'todo-cli';

    /**
     * Commands that should be removed in production.
     *
     * @var string[]
     */
    protected $developmentOnlyCommands = [
        \NunoMaduro\Collision\Adapters\Laravel\Commands\TestCommand::class,
        \Intonate\TinkerZero\Console\TinkerZeroCommand::class,
        \Illuminate\Database\Console\WipeCommand::class,
        \Illuminate\Database\Console\Migrations\MigrateCommand::class,
        \Illuminate\Database\Console\Migrations\FreshCommand::class,
        \Illuminate\Database\Console\Migrations\InstallCommand::class,
        \Illuminate\Database\Console\Migrations\RefreshCommand::class,
        \Illuminate\Database\Console\Migrations\ResetCommand::class,
        \Illuminate\Database\Console\Migrations\RollbackCommand::class,
        \Illuminate\Database\Console\Migrations\StatusCommand::class,
        \Illuminate\Database\Console\Migrations\MigrateMakeCommand::class,
    ];

    /**
     * The application's bootstrap classes.
     *
     * @var string[]
     */
    protected $bootstrappers = [
        \App\Bootstrap\SetAppName::class,
        \LaravelZero\Framework\Bootstrap\CoreBindings::class,
        \LaravelZero\Framework\Bootstrap\LoadEnvironmentVariables::class,
        \LaravelZero\Framework\Bootstrap\LoadConfiguration::class,
        \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
        \LaravelZero\Framework\Bootstrap\RegisterFacades::class,
        \LaravelZero\Framework\Bootstrap\RegisterProviders::class,
        \Illuminate\Foundation\Bootstrap\BootProviders::class,
    ];
}
