<?php

namespace Tests;

use OpenOnMake\File;
use ReflectionClass;
use OpenOnMake\Check;
use OpenOnMake\OpenFile;
use OpenOnMake\CommandInfo;
use Orchestra\Testbench\TestCase;
use OpenOnMake\Testing\IsGenerator;
use OpenOnMake\Listeners\OpenOnMake;
use OpenOnMake\Testing\NotGenerator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Events\CommandFinished;
use Symfony\Component\Console\Input\ArgvInput;

class CommandInfoTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
    }

    // public function simulateCommand($artisanString)
    // {
    //     return new CommandInfo(new class {

    //         public function __construct('make:model', ArgvInput $input) {
    //             $this->var = $var;
    //         }
    //         public $command = 'make:model';
    //         public $input = new class {
    //             public function getArgument($var)
    //             {
    //                 return 'it works';
    //             }

    //             public function getOption($var)
    //             {
    //                 return 'optio work too';
    //             }
    //         };
    //     });
    // }

    // /** @test */
    // public function it_checks_if_empty_command_is_not_a_make_model_command()
    // {
    //     $commandInfo = $this->simulateCommand('make:model');
    //     $this->assertTrue($commandInfo->isMakeCommand());
    // }
}
