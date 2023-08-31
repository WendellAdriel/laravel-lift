<?php

declare(strict_types=1);

namespace WendellAdriel\Lift\Providers;

use Illuminate\Support\ServiceProvider;
use WendellAdriel\Lift\Console\Commands\LiftMigration;

final class LiftServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(LiftMigration::class);
        }
    }
}
