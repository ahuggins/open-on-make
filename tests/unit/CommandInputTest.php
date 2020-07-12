<?php

namespace Tests;

use OpenOnMake\File;
use ReflectionClass;
use OpenOnMake\Check;
use OpenOnMake\OpenFile;
use OpenOnMake\CommandInfo;
use OpenOnMake\CommandInput;
use Orchestra\Testbench\TestCase;
use OpenOnMake\Testing\IsGenerator;
use OpenOnMake\Listeners\OpenOnMake;
use OpenOnMake\Testing\NotGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Events\CommandFinished;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\StringInput;

class CommandInputTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
    }

    public function makeCommandInput($args)
    {
        return new CommandInput(new StringInput($args));
    }

    /** @test */
    public function it_should_not_have_options_if_none_passed()
    {
        $commandInput = $this->makeCommandInput('make:model SomeName');
        $this->assertFalse($commandInput->hasOptions());
    }

    /** @test */
    public function it_should_have_options_if_passed()
    {
        $commandInput = $this->makeCommandInput('make:model SomeName -c');
        $this->assertTrue($commandInput->hasOptions());
    }

    /** @test */
    public function it_returns_all_options_in_getCollection()
    {
        $commandInput = $this->makeCommandInput('make:model SomeName -c');
        $this->assertCount(3, $commandInput->getCollection());
    }

    /** @test */
    public function it_returns_all_options_in_getOptions()
    {
        $commandInput = $this->makeCommandInput('make:model SomeName -c');
        $this->assertCount(1, $commandInput->getOptions());
        $this->assertEquals(array_values(['-c']), array_values($commandInput->getOptions()));
    }
}
