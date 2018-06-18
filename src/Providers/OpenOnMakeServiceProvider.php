<?php

namespace OpenOnMake\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
    }
}
