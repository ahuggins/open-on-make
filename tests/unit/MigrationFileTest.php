<?php

namespace Tests;

use OpenOnMake\Files\MigrationFile;
use Orchestra\Testbench\TestCase;

class MigrationFileTest extends TestCase
{
    /** @test */
    public function it_returns_latest_migration_path()
    {
        $path = MigrationFile::getLatestMigrationFile();
        $this->assertStringContainsString('laravel/database/migrations', $path);
    }
}
