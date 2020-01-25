<?php

namespace Tests;

use \Mockery;
use Mockery\Mock;
use OpenOnMake\File;
use OpenOnMake\Check;
use OpenOnMake\OpenFile;
use Orchestra\Testbench\TestCase;

class FileTest extends TestCase
{
    public function setUp() : void
    {
        $this->open = Mockery::mock(OpenFile::class);
        $this->file = new File($this->open);
        parent::setUp();
    }

    /** @test */
    public function it_adds_php_extension_to_name_of_thing_being_generated()
    {
        $filename = $this->file->getFileName('NameToGenerate');
        $this->assertEquals('NameToGenerate.php', $filename);
    }

    /** @test */
    public function it_adds_php_extension_to_name_of_thing_being_generated_and_replaces_two_backslashes_with_forward_slash()
    {
        $filename = $this->file->getFileName('Models\\NameToGenerate');
        $this->assertEquals('Models/NameToGenerate.php', $filename);
    }

    /** @test */
    public function it_returns_view_filename_if_command_is_view()
    {
        $filename = $this->file->filename('make:view', 'someview');
        $this->assertEquals('someview.blade.php', $filename);
    }

    /** @test */
    public function it_returns_filename()
    {
        $filename = $this->file->filename('make:model', 'Model');
        $this->assertEquals('Model.php', $filename);
    }

    /** @test */
    public function it_calls_open_when_opening_additional_files()
    {
        $this->open->expects('open')->once();

        $this->file->openAdditionalFile('', 'SomeName', '-c');
    }

    /** @test */
    public function it_calls_open_when_opening_latest_migration()
    {
        $this->open->expects('open')->once();

        $this->file->openLatestMigration();
    }

    /** @test */
    public function it_tries_to_find_the_file()
    {
        $path = $this->file->find('somefile.php');

        $this->assertEquals('', $path);
    }

    /** @test */
    public function it_tries_to_find_the_file_in_base_path()
    {
        $path = $this->file->find('packages.php');

        $this->assertStringContainsString('orchestra/testbench-core/laravel/bootstrap/cache/packages.php', $path);
    }

    /** @test */
    public function it_opens_migration_generated_in_addition_to_model()
    {
        $this->open->expects('open')->once();
        $this->file->openFilesGeneratedInAdditionToModel('-m', 'SomeModelName');
    }

    /** @test */
    public function it_opens_additional_files_generated_in_addition_to_model()
    {
        $this->open->expects('open')->once();
        $this->file->openFilesGeneratedInAdditionToModel('-r', 'SomeModelName');
    }

    /** @test */
    public function it_open_all_types_when_flag_present()
    {
        $this->open->expects('open')->times(6);
        $this->file->openAllTypes('-c', 'SomeModelName');
    }

    protected function getPackageProviders($app)
    {
        return ['OpenOnMake\Providers\OpenOnMakeServiceProvider'];
    }
}
