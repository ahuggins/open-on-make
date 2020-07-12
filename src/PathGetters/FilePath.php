<?php

namespace OpenOnMake\PathGetters;

use OpenOnMake\File;
use OpenOnMake\CommandInfo;
use OpenOnMake\PathGetters\PathForTestCommand;
use OpenOnMake\PathGetters\PathForCommandCommand;
use OpenOnMake\PathGetters\PathForFactoryCommand;

class FilePath
{
    private $file;
    
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function determine(CommandInfo $commandInfo)
    {
        if ($commandInfo->isTestCommand()) {
            return (new PathForTestCommand($this->file))->handle($commandInfo);
        } elseif ($commandInfo->isFactoryCommand()) {
            return (new PathForFactoryCommand($this->file))->handle($commandInfo);
        } elseif ($commandInfo->isCommandCommand()) {
            return (new PathForCommandCommand($this->file))->handle($commandInfo);
        }
        return $this->file->find($this->file->filename($commandInfo->getCommandString(), $commandInfo->getArgName()));
    }
}
