<?php

namespace OpenOnMake\PathGetters;

use OpenOnMake\File;
use OpenOnMake\CommandInfo;
use OpenOnMake\Openers\MigrationFile;

class FilePath
{
    private $file;
    private $migration;
    
    public function __construct(File $file, MigrationFile $migration)
    {
        $this->file = $file;
        $this->migration = $migration;
    }

    public function determine(CommandInfo $commandInfo)
    {
        if ($commandInfo->isMigrationCommand()) {
            return $this->migration->getLatestMigration();
        }
        return $this->file->find($commandInfo);
    }
}
