<?php

namespace OpenOnMake\PathGetters;

use OpenOnMake\CommandInfo;

class PathForFactoryCommand extends AbstractPath
{
    public function handle(CommandInfo $commandInfo)
    {
        $name = $this->file->filename($commandInfo->getCommandString(), $commandInfo->getArgName());
        $file = new \SplFileInfo($name);
        return $this->file->find($file);
    }
}
