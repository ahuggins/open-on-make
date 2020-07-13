<?php

namespace OpenOnMake\PathGetters;

use OpenOnMake\File;
use OpenOnMake\CommandInfo;
use OpenOnMake\Openers\MigrationFile;
use OpenOnMake\PathGetters\PathForCommandCommand;

class FilePath
{
    private $file;
    
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function determine(CommandInfo $commandInfo)
    {
        if ($commandInfo->isMigrationCommand()) {
            MigrationFile::open();
            exit;
        } elseif ($commandInfo->isCommandCommand()) {
            return (new PathForCommandCommand)->handle($commandInfo);
        }
        return $this->file->find($commandInfo);
    }
}
