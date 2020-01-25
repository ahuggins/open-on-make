<?php

namespace Tests;

use OpenOnMake\File;
use ReflectionClass;
use OpenOnMake\Check;
use Orchestra\Testbench\TestCase;
use OpenOnMake\Listeners\OpenOnMake;
use Illuminate\Filesystem\Filesystem;

class InstallTest extends TestCase
{
    /** @test */
    public function it_installs_env_for_open()
    {
        exec('touch vendor/orchestra/testbench-core/laravel/.env');

        $this->artisan('open:install')->expectsQuestion('What editor do you want to use?', 'VSCode');

        exec('rm vendor/orchestra/testbench-core/laravel/.env');
    }

    protected function getPackageProviders($app)
    {
        return ['OpenOnMake\Providers\OpenOnMakeServiceProvider'];
    }
}
