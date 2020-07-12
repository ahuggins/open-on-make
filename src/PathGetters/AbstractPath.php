<?php

namespace OpenOnMake\PathGetters;

use OpenOnMake\File;
use OpenOnMake\CommandInfo;

abstract class AbstractPath
{
    private File $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }
    
    abstract public function handle(CommandInfo $commandInfo);
}
