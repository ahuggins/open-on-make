<?php

namespace Tests;

use OpenOnMake\Files\MigrationFile;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MigrationFileTest extends TestCase
{
    #[Test]
    public function it_returns_latest_migration_path()
    {
        $path = MigrationFile::getLatestMigrationFile();
        $this->assertStringContainsString('laravel/database/migrations', $path);
    }
}
