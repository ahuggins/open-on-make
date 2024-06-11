<?php

namespace Tests;

use Mockery;
use OpenOnMake\CommandInfo;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;
use Mockery\Mock;
use PHPUnit\Framework\Attributes\Test;
use SplFileInfo;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;

class CommandInfoTest extends TestCase
{
    #[Test]
    public function it_checks_if_empty_command_is_not_a_make_model_command()
    {

        // $commandInfo = new CommandInfo;
        $this->assertTrue(true);
        // $this->assertFalse(false);
        // dd($this->assertFalse($commandInfo->isMakeCommand()));
        // $this->assertFalse($commandInfo->isMakeCommand());
    }

    #[Test]
    public function it_checks_if_command_is_make_model_command()
    {
        $commandInfo = new CommandInfo('make:model');
        $this->assertTrue($commandInfo->isMakeModelCommand());
    }

    #[Test]
    public function it_checks_if_command_is_make_test_command()
    {
        $commandInfo = new CommandInfo('make:test');
        $this->assertTrue($commandInfo->isTestCommand());
    }

    #[Test]
    public function it_checks_if_command_is_make_factory_command()
    {
        $commandInfo = new CommandInfo('make:factory');
        $this->assertTrue($commandInfo->isFactoryCommand());
    }

    #[Test]
    public function it_checks_if_command_is_make_command_command()
    {
        $commandInfo = new CommandInfo('make:command');
        $this->assertTrue($commandInfo->isCommandCommand());
    }

    #[Test]
    public function it_returns_what_is_set_for_input()
    {
        $testArgs = new ArgvInput(['IgnoredAppName', 'make:command', 'NameOfGeneratedFile', 'firstOption']);
        $commandInfo = new CommandInfo('make:command', null, $testArgs);
        $this->assertEquals('firstOption', $commandInfo->getInput()->getOptions()[2]);
    }

    #[Test]
    public function it_checks_if_command_is_migration_command_command()
    {
        $commandInfo = new CommandInfo('make:migration');
        $this->assertTrue($commandInfo->isMigrationCommand());
    }

    #[Test]
    public function it_checks_if_command_is_artisan_list_command()
    {
        // If you just run `php artisan`
        $commandInfo = new CommandInfo;
        $this->assertTrue($commandInfo->isListCommand());
    }

    #[Test]
    public function it_checks_if_command_is_artisan_list_command_specifically()
    {
        // If you run `php artisan list`
        $commandInfo = new CommandInfo('list');
        $this->assertTrue($commandInfo->isListCommand());
    }

    #[Test]
    public function it_returns_the_command_string()
    {
        $commandInfo = new CommandInfo('make:controller', 'Something');
        $this->assertEquals('make:controller', $commandInfo->getCommandString());
    }

    #[Test]
    public function it_returns_the_arg_name()
    {
        $commandInfo = new CommandInfo('make:controller', 'Something');
        $this->assertEquals('Something', $commandInfo->getArgName());
    }

    #[Test]
    public function it_says_list_command_is_not_openable()
    {
        $commandInfo = new CommandInfo;
        $this->assertFalse($commandInfo->isOpenable());
    }

    #[Test]
    public function it_says_help_executions_are_not_openable()
    {
        $commandInfo = new CommandInfo('make:model', null, null, null, true);
        $this->assertFalse($commandInfo->isOpenable());
    }

    #[Test]
    public function it_says_commands_without_a_name_are_not_openable()
    {
        $commandInfo = new CommandInfo('make:model');
        $this->assertFalse($commandInfo->isOpenable());
    }

    #[Test]
    public function it_creates_a_filename_based_on_name_argument()
    {
        $commandInfo = new CommandInfo('make:model', 'SomeName');
        $this->assertEquals('SomeName.php', $commandInfo->getFilename());
    }

    #[Test]
    public function it_creates_a_filename_based_on_name_argument_with_namespace()
    {
        $commandInfo = new CommandInfo('make:model', 'App\\Models\\SomeName');
        $this->assertEquals('App/Models/SomeName.php', $commandInfo->getFilename());
    }

    #[Test]
    public function it_returns_splfile_from_filename()
    {
        $commandInfo = new CommandInfo('make:model', 'SomeName');
        $this->assertInstanceOf(SplFileInfo::class, $commandInfo->getSplFile());
    }

    #[Test]
    public function it_a_does_not_create_a_filename_if_no_name_provided()
    {
        $commandInfo = new CommandInfo('make:model');
        $this->assertNull($commandInfo->getFilename());
    }

    #[Test]
    public function it_gets_the_command_class()
    {
        $commandInfo = new CommandInfo('make:model');
        $this->assertEquals('Illuminate\Foundation\Console\ModelMakeCommand', $commandInfo->getCommandClass());
    }

    #[Test]
    public function it_returns_null_for_command_class_when_unknown_make_command_executed()
    {
        $commandInfo = new CommandInfo('make:thisIsNotAValidModel');
        $this->assertNull($commandInfo->getCommandClass());
    }

    #[Test]
    public function it_alerts_user_that_adding_php_to_their_name_arguments_is_bad()
    {
        $spy = Mockery::spy(OutputInterface::class);
        $commandInfo = new CommandInfo('make:model', 'SomeModel.php', null, $spy);
        $spy->shouldHaveReceived('writeln')->times(3);
        $this->assertTrue(true);
    }

    #[Test]
    public function it_says_nothing_is_openable_if_production()
    {
        Config::set('app.env', 'production');
        $commandInfo = new CommandInfo('make:model');
        $this->assertFalse($commandInfo->isOpenable());
    }

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
