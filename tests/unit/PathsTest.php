<?php

namespace Tests;

use OpenOnMake\Paths;
use Orchestra\Testbench\TestCase;
use OpenOnMake\Exceptions\UnsupportedCommandType;

class PathsTest extends TestCase
{
    /** @test */
    public function it_returns_an_object_path_for_a_command_key()
    {
        $this->assertEquals('Illuminate\Foundation\Console\ModelMakeCommand', Paths::getCommandPath('model'));
    }

    /** @test */
    public function it_returns_null_if_command_key_not_exists()
    {
        $this->expectException(UnsupportedCommandType::class);

        $this->assertNull(Paths::getCommandPath('notACommandKey'));
    }

    /** @test */
    public function it_returns_the_command_path_from_command_string()
    {
        $this->assertEquals(
            'Illuminate\Foundation\Console\ModelMakeCommand',
            Paths::getCommandClass('make:model')
        );
    }

    /** @test */
    public function it_returns_null_if_command_string_unknown()
    {
        $this->assertNull(Paths::getCommandClass('make:somethingThatDoesNotExist'));
    }

    /** @test */
    public function it_returns_path_of_existing_key()
    {
        $this->assertEquals(
            'app/Http/Controllers/',
            Paths::getPath('controller')
        );
    }

    protected function getPackageProviders($app)
    {
        return ['OpenOnMake\Providers\OpenOnMakeServiceProvider'];
    }
}