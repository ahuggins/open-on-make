<?php

namespace Tests;

use OpenOnMake\Paths;
use OpenOnMake\Options;
use Orchestra\Testbench\TestCase;
use OpenOnMake\Exceptions\UnsupportedCommandType;

class OptionsTest extends TestCase
{
    /** @test */
    public function it_returns_the_command_type_from_option()
    {
        foreach (Options::$options as $flag => $type) {
            $this->assertEquals($type, Options::getOption($flag));    
        }
    }

    /** @test */
    public function it_checks_if_command_is_resource()
    {
        $this->assertTrue(Options::isResource('-r'));
        $this->assertTrue(Options::isResource('--resource'));
        $this->assertTrue(Options::isResource('resource'));
    }

    /** @test */
    public function it_checks_if_command_is_migration()
    {
        $this->assertTrue(Options::isMigration('-m'));
        $this->assertTrue(Options::isMigration('--migration'));
        $this->assertTrue(Options::isMigration('migration'));
    }

    /** @test */
    public function it_checks_if_command_is_all()
    {
        $this->assertTrue(Options::isAll('-a'));
        $this->assertTrue(Options::isAll('--all'));
    }

    /** @test */
    public function it_checks_if_the_passed_option_exists()
    {
        $this->assertTrue(Options::exist('-c'));
    }

    /** @test */
    public function it_checks_if_the_passed_option_does_not_exists()
    {
        $this->assertFalse(Options::exist('--notExistingOption'));
    }
}