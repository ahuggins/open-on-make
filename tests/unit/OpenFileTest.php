<?php

namespace Tests;

use \Mockery;
use Mockery\Mock;
use OpenOnMake\File;
use OpenOnMake\Check;
use OpenOnMake\OpenFile;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

class OpenFileTest extends TestCase
{
    public $open;

    public function setUp(): void
    {
        $this->open = new OpenFile;
        parent::setUp();
    }

    #[Test]
    public function it_opens_files()
    {
        $this->assertTrue(OpenFile::open('test'));
    }

    protected function getPackageProviders($app)
    {
        return ['OpenOnMake\Providers\OpenOnMakeServiceProvider'];
    }
}
