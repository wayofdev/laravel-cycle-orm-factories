<?php

declare(strict_types=1);

namespace WayOfDev\DatabaseSeeder\Bridge\Laravel\Providers;

use Illuminate\Support\ServiceProvider;

final class DatabaseSeederServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../../../config/database-seeder.php' => config_path('database-seeder.php'),
            ], 'config');

            $this->registerConsoleCommands();
        }
    }

    private function registerConsoleCommands(): void
    {
        $this->commands([]);
    }
}
