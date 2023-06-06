<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WayOfDev\DatabaseSeeder\Bridge\Laravel\Providers\DatabaseSeederServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            DatabaseSeederServiceProvider::class,
        ];
    }
}
