<?php

namespace Xiso\InertiaUI;

use Illuminate\Support\ServiceProvider;
use Xiso\InertiaUI\Console\FormMakeCommand;

class InertiaUIServiceProvider extends ServiceProvider
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
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    FormMakeCommand::class,
                ],
            );
        }
    }
}
