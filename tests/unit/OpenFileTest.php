<?php

namespace Tests;

use \Mockery;
use Mockery\Mock;
use OpenOnMake\File;
use OpenOnMake\Check;
use OpenOnMake\OpenFile;
use Orchestra\Testbench\TestCase;

class OpenFileTest extends TestCase
{
    public function setUp() : void
    {
        $this->open = new OpenFile;
        parent::setUp();
    }

    /** @test */
    public function it_opens_files()
    {
        $this->assertTrue($this->open->open('test'));
    }

    protected function getPackageProviders($app)
    {
        return ['OpenOnMake\Providers\OpenOnMakeServiceProvider'];
    }
}
