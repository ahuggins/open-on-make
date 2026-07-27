<?php

namespace Tests;

use OpenOnMake\CommandInfo;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;

class SaloonCommandTest extends TestCase
{
    /** @test */
    public function it_treats_saloon_generator_commands_as_make_commands()
    {
        $commandInfo = new CommandInfo('saloon:request', 'GetServersRequest');
        $this->assertTrue($commandInfo->isMakeCommand());
    }

    /** @test */
    public function it_says_saloon_commands_with_a_name_are_openable()
    {
        $commandInfo = new CommandInfo('saloon:connector', 'ForgeConnector');
        $this->assertTrue($commandInfo->isOpenable());
    }

    /** @test */
    public function it_says_saloon_commands_without_a_name_are_not_openable()
    {
        $commandInfo = new CommandInfo('saloon:list');
        $this->assertFalse($commandInfo->isOpenable());
    }

    /** @test */
    public function it_respects_configured_prefixes()
    {
        Config::set('open-on-make.prefixes', ['make:']);
        $commandInfo = new CommandInfo('saloon:request', 'GetServersRequest');
        $this->assertFalse($commandInfo->isMakeCommand());
    }

    /** @test */
    public function it_still_treats_regular_make_commands_as_make_commands()
    {
        $commandInfo = new CommandInfo('make:model', 'SomeModel');
        $this->assertTrue($commandInfo->isOpenable());
    }
}
