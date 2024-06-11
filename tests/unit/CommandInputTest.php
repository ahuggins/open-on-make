<?php

namespace Tests;

use OpenOnMake\CommandInput;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\Console\Input\StringInput;

class CommandInputTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function makeCommandInput($args)
    {
        return new CommandInput(new StringInput($args));
    }

    #[Test]
    public function it_should_not_have_options_if_none_passed()
    {
        $commandInput = $this->makeCommandInput('make:model SomeName');
        $this->assertFalse($commandInput->hasOptions());
    }

    #[Test]
    public function it_should_have_options_if_passed()
    {
        $commandInput = $this->makeCommandInput('make:model SomeName -c');
        $this->assertTrue($commandInput->hasOptions());
    }

    #[Test]
    public function it_returns_all_options_in_getCollection()
    {
        $commandInput = $this->makeCommandInput('make:model SomeName -c');
        $this->assertCount(3, $commandInput->getCollection());
    }

    #[Test]
    public function it_returns_all_options_in_getOptions()
    {
        $commandInput = $this->makeCommandInput('make:model SomeName -c');
        $this->assertCount(1, $commandInput->getOptions());
        $this->assertEquals(array_values(['-c']), array_values($commandInput->getOptions()));
    }

    #[Test]
    public function it_returns_className_from_argument_with_full_qualified_classname()
    {
        $commandInput = $this->makeCommandInput('make:model App\\\Models\\\SomeName');
        $this->assertEquals('SomeName', $commandInput->getClassNameOfNameArgument());
    }
}
