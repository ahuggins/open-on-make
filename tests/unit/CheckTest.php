<?php

namespace Tests;

use ReflectionClass;
use OpenOnMake\Check;
use Orchestra\Testbench\TestCase;
use OpenOnMake\Listeners\OpenOnMake;
use Illuminate\Filesystem\Filesystem;

class CheckTest extends TestCase
{
    /** @test */
    public function it_verifies_the_command_run_is_a_make_command()
    {
        $this->assertTrue(Check::executedCommandWasMakeCommand('make:model'));
    }

    /** @test */
    public function it_says_non_make_commands_are_not_Make_command()
    {
        $this->assertFalse(Check::executedCommandWasMakeCommand('key:generate'));
    }

    /** @test */
    public function it_verifies_a_view_command()
    {
        $this->assertTrue(Check::isViewCommand('make:view'));
    }

    /** @test */
    public function it_verifies_NOT_view_command()
    {
        $this->assertFalse(Check::isViewCommand('make:model'));
    }

    /** @test */
    public function it_checks_if_command_is_openable()
    {
        $this->assertTrue(Check::commandIsOpenable('make:model'));
    }

    /** @test */
    public function it_checks_if_command_is_a_make_model_command()
    {
        $this->assertTrue(Check::isMakeModelCommand('make:model'));
    }

    /** @test */
    public function it_checks_if_command_is_not_a_make_model_command()
    {
        $this->assertFalse(Check::isMakeModelCommand('make:view'));
    }

    /** @test */
    public function it_checks_if_command_is_not_flagged_with_help_flag()
    {
        $this->assertTrue(Check::notCommandHelp());
    }

    /** @test */
    public function it_checks_if_command_is_flagged_with_help_flag()
    {
        $this->assertFalse(Check::notCommandHelp(true));
    }

    /** @test */
    public function it_checks_if_env_is_not_production()
    {
        $this->assertTrue(Check::envNotProduction());   
    }

    /** @test */
    public function it_checks_if_generator_command_is_not_subclass_of_Illumiate_Generator_Command()
    {
        $this->assertFalse(Check::isSubClassOfGeneratorCommand(new ReflectionClass(new NotGenerator)));
    }

    /** @test */
    public function it_checks_if_generator_command_is_subclass_of_Illumiate_Generator_Command()
    {
        $reflection = new ReflectionClass($this->createGeneratorChildClass());
        $this->assertTrue(Check::isSubClassOfGeneratorCommand($reflection));
    }

    private function createGeneratorChildClass()
    {
        return new IsGenerator(new Filesystem);
    }
}

// Classes only used for testing, just need a class that has GeneratorCommand as parent, and one that doesn't

class NotGenerator extends OpenOnMake
{
    
}

class IsGenerator extends \Illuminate\Console\GeneratorCommand {
    public function getStub()
    {
        # code...
    }
}

