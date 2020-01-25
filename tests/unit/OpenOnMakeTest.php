<?php

namespace Tests;

use Mockery;
use Mockery\Mock;
use OpenOnMake\File;
use OpenOnMake\Check;
use Orchestra\Testbench\TestCase;
use OpenOnMake\Files\MigrationFile;
use OpenOnMake\Listeners\OpenOnMake;
use Illuminate\Console\Events\CommandFinished;

class OpenOnMakeTest extends TestCase
{
    public function setUp() : void
    {
        // $this->file = Mockery::mock(File::class);
        // $this->listener = new OpenOnMake($this->file);
        parent::setUp();
    }

    /** @test */
    public function it_calls_file_open_when_a_make_command_is_executed()
    {
        $this->instance(File::class, Mockery::mock(File::class, function ($mock) {
            $mock->shouldReceive('open')->once();
        }));
    
        $this->artisan('make:model', ['name' => 'Some']);
    }

    /** @test */
    public function it_calls_file_open_when_a_make_command_is_executed_and_checks_flags()
    {
        $this->instance(File::class, Mockery::mock(File::class, function ($mock) {
            $mock->shouldReceive('open')->once();
            $mock->shouldReceive('openAllTypes')->once();
        }));
    
        $this->artisan('make:model', [
            'name' => 'Some',
            '-a'
        ]);

        exec('rm vendor/orchestra/testbench-core/laravel/app/Some.php');
    }

    /** @test */
    public function it_calls_openLatestMigration_open_when_a_make_migration_command_is_executed()
    {
        $this->instance(File::class, Mockery::mock(File::class, function ($mock) {
            $mock->shouldReceive('open')->once();
        }));
    
        $this->artisan('make:migration', [
            'name' => 'some_migration_name',
        ]);
        
        $latestPath = MigrationFile::getLatestMigrationFile();

        exec('rm ' . $latestPath);
    }

    /** @test */
    public function it_calls_file_open_when_a_make_command_is_executed_and_does_controller_flag()
    {
        $this->instance(File::class, Mockery::mock(File::class, function ($mock) {
            $mock->shouldReceive('open')->once();
            $mock->shouldReceive('openFilesGeneratedInAdditionToModel')->once();
        }));
    
        $this->artisan('make:model', [
            'name' => 'Some',
            '-c'
        ]);

        exec('rm vendor/orchestra/testbench-core/laravel/app/Some.php');
    }

    /** @test */
    public function it_calls_unknown_make_command_and_still_tries_to_find_file()
    {
        $this->instance(File::class, Mockery::mock(File::class, function ($mock) {
            $mock->shouldReceive('open')->once();
            $mock->shouldReceive('find')->once();
            $mock->shouldReceive('filename')->once();
        }));
    
        $this->artisan('make:onlyClassExists', [
            'name' => 'SomeThingToDo',
        ]);

        // exec('rm vendor/orchestra/testbench-core/laravel/app/Some.php');
    }

    /** @test */
    public function it_opens_tests()
    {
        $this->instance(File::class, Mockery::mock(File::class, function ($mock) {
            $mock->shouldReceive('open')->once();
            $mock->shouldReceive('find')->once()->andReturn('vendor/orchestra/testbench-core/tests/Feature/ATestName.php');
            $mock->shouldReceive('filename')->once()->andReturn('ATestName.php');
        }));
    
        $this->artisan('make:test', [
            'name' => 'ATestName',
        ]);
    }

    /** @test */
    public function it_opens_factory()
    {
        $this->instance(File::class, Mockery::mock(File::class, function ($mock) {
            $mock->shouldReceive('open')->once();
            $mock->shouldReceive('find')->once()->andReturn('vendor/orchestra/testbench-core/laravel/database/factories/SomeFactoryName.php');
            $mock->shouldReceive('filename')->once()->andReturn('SomeFactoryName.php');
        }));
    
        $this->artisan('make:factory', [
            'name' => 'SomeFactoryName',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return ['OpenOnMake\Providers\OpenOnMakeServiceProvider'];
    }
}
