<?php

namespace OpenOnMake\Providers;

use OpenOnMake\Testing\IsGenerator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\ModelMakeCommand;
use ImLiam\EnvironmentSetCommand\EnvironmentSetCommand;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;

class OpenOnMakeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'../../config/open-on-make.php',
           'open-on-make'
        );

        $this->publishes([
            __DIR__.'/../config/open-on-make.php' => config_path('open-on-make.php')
        ], 'open-on-make');

        if (config('open-on-make.enabled')) {
            Event::listen(
                'Illuminate\Console\Events\CommandFinished',
                'OpenOnMake\Listeners\OpenOnMake'
            );
        }


        $commands = [
            \OpenOnMake\Commands\InstallEnvCommand::class,
        ];
        
        if ($this->app->environment() === 'testing') {
            $commands[] = ModelMakeCommand::class;
            $commands[] = MigrateMakeCommand::class;
            $commands[] = IsGenerator::class;
            $commands[] = EnvironmentSetCommand::class;
        }

        if ($this->app->runningInConsole()) {
            $this->commands($commands);
        }
    }
}
