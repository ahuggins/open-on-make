<?php

namespace Tests;

use Mockery;
use OpenOnMake\CommandInfo;
use Orchestra\Testbench\TestCase;
use Illuminate\Console\Events\CommandFinished;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandInfoTest extends TestCase
{
    /** @test */
    public function it_checks_if_empty_command_is_not_a_make_model_command()
    {
        $commandInfo = $this->simulateCommand('');
        $this->assertFalse($commandInfo->isMakeCommand());
    }

    /** @test */
    public function it_checks_if_command_is_make_model_command()
    {
        $commandInfo = $this->simulateCommand('make:model');
        $this->assertTrue($commandInfo->isMakeCommand());
    }

    /** @test */
    public function it_checks_if_command_is_artisan_list_command()
    {
        // If you just run `php artisan`
        $commandInfo = $this->simulateCommand();
        $this->assertTrue($commandInfo->isListCommand());
    }

    /** @test */
    public function it_checks_if_command_is_artisan_list_command_specifically()
    {
        // If you run `php artisan list`
        $commandInfo = $this->simulateCommand('list');
        $this->assertTrue($commandInfo->isListCommand());
    }

    /** @test */
    public function it_returns_the_command_string()
    {
        $commandInfo = $this->simulateCommand('make:controller', 'Something');
        $this->assertEquals('make:controller', $commandInfo->getCommandString());
    }

    /** @test */
    public function it_returns_the_arg_name()
    {
        $commandInfo = $this->simulateCommand('make:controller', 'Something');
        $this->assertEquals('Something', $commandInfo->getArgName());
    }

    public function setUp() : void
    {
        parent::setUp();
    }

    private function mockInput($name)
    {
        $argName = $name == '' ? [] : ['name' => $name];
        $inputMock = Mockery::mock(InputInterface::class);
        $inputMock->shouldReceive('getArguments')->once()->andReturn($argName);
        $inputMock->shouldReceive('getArgument')->with('name')->once()->andReturn($name);
        $name == '' ? $inputMock->shouldReceive('getOption')->with('help')->times(0) : $inputMock->shouldReceive('getOption')->with('help')->once()->andReturn(false);
        return $inputMock;
    }

    /**
     * Not used for anything other than depency resolution
     *
     * @return void
     */
    private function mockOutput()
    {
        return Mockery::mock(OutputInterface::class);
    }

    protected function tearDown() : void
    {
        Mockery::close();
    }

    private function simulateCommand($commandString = null, $name = '', $options = [])
    {
        $inputMock = $this->mockInput($name);
        $outputMock = $this->mockOutput();
        return new CommandInfo(
            new CommandFinished(
                $commandString,
                $inputMock,
                $outputMock,
                0
            )
        );
    }
}
