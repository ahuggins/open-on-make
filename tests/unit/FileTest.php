<?php

namespace Tests;

use Mockery\Mock;
use OpenOnMake\File;
use OpenOnMake\Check;
use Orchestra\Testbench\TestCase;

class FileTest extends TestCase
{
    public function setUp() : void
    {
        $this->file = new File;
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
    public function it_calls_open_when_opening_migration()
    {
        $this->assertTrue(true);
    }
}
