<?php

namespace App\Bootstrap;

use App\Kernel;
use LaravelZero\Framework\Application;
use LaravelZero\Framework\Contracts\BoostrapperContract;

class SetAppName implements BoostrapperContract
{
    public function bootstrap(Application $app): void
    {
        $app->instance('name', Kernel::APP_NAME);
    }
}
