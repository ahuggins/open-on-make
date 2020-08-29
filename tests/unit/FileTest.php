<?php

namespace Tests;

use \Mockery;
use OpenOnMake\File;
use OpenOnMake\Options;
use OpenOnMake\OpenFile;
use Orchestra\Testbench\TestCase;
use Symfony\Component\Finder\Finder;

class FileTest extends TestCase
{
    public function setUp() : void
    {
        $this->open = Mockery::mock(OpenFile::class);
        $this->file = new File($this->open, new Options, new Finder);
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
    public function it_calls_open_when_opening_additional_files()
    {
        $this->open->expects('open')->once();

        $this->file->openAdditionalFile('', 'SomeName', '-c');
    }

    /** @test */
    public function it_opens_additional_files_generated_in_addition_to_model()
    {
        $this->open->expects('open')->once();
        $this->file->openFilesGeneratedInAdditionToModel('-r', 'SomeModelName');
    }

    /** @test */
    public function it_delegates_open_to_open_file_class()
    {
        $this->open->expects('open')->once();
        $this->file->open('something');
    }

    protected function getPackageProviders($app)
    {
        return ['OpenOnMake\Providers\OpenOnMakeServiceProvider'];
    }
}
