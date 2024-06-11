<?php

namespace Tests;

use OpenOnMake\Options;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OptionsTest extends TestCase
{
    private $options;

    public function setUp(): void
    {
        $this->options = new Options;
    }
    #[Test]
    public function it_returns_the_command_type_from_option()
    {
        foreach ($this->options->getOptions() as $flag => $type) {
            $this->assertEquals($type, $this->options->getOption($flag));
        }
    }

    #[Test]
    public function it_checks_if_command_is_resource()
    {
        $this->assertTrue($this->options->isResource('-r'));
        $this->assertTrue($this->options->isResource('--resource'));
        $this->assertTrue($this->options->isResource('resource'));
    }

    #[Test]
    public function it_checks_if_command_is_migration()
    {
        $this->assertTrue($this->options->isMigration('-m'));
        $this->assertTrue($this->options->isMigration('--migration'));
        $this->assertTrue($this->options->isMigration('migration'));
    }

    #[Test]
    public function it_checks_if_command_is_all()
    {
        $this->assertTrue($this->options->isAll('-a'));
        $this->assertTrue($this->options->isAll('--all'));
    }

    #[Test]
    public function it_checks_if_the_passed_option_exists()
    {
        $this->assertTrue($this->options->exist('-c'));
    }

    #[Test]
    public function it_checks_if_the_passed_option_does_not_exists()
    {
        $this->assertFalse($this->options->exist('--notExistingOption'));
    }
}
