<?php

namespace OpenOnMake\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OpenOnMakeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->publishes([
            __DIR__.'/../config/open-on-make.php' => config_path('open-on-make.php')
        ], 'open-on-make');

        Event::listen('Illuminate\Console\Events\CommandFinished', function($event)
        {
            new OpenOnMake\Listeners\OpenOnMake($event);
        });
    }
}
