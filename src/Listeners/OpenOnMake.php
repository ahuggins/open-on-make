<?php

namespace OpenOnMake\Listeners;

use OpenOnMake\File;
use OpenOnMake\CommandInfo;
use OpenOnMake\PathGetters\FilePath;

class OpenOnMake
{
    protected $event;
    protected $argName;
    protected $commandString;
    protected $help;
    private $commandInfo;
    private $filePath;

    public function __construct(File $file, FilePath $filePath)
    {
        $this->file = $file;
        $this->filePath = $filePath;
    }

    public function handle($event)
    {
        $this->commandInfo = new CommandInfo($event);
        if ($this->commandInfo->isOpenable()) {
            $path = $this->filePath->determine($this->commandInfo);
            
            $this->file->open($path);
            
            $this->checkForFlags();
        }
    }

    public function checkForFlags()
    {
        if (!$this->commandInfo->isMakeModelCommand()) {
            return null;
        }

        $input = $this->commandInfo->getInput();
        
        if ($input->hasOptions()) {
            $name = $input->getClassNameOfNameArgument();
            foreach ($input->getOptions() as $option) {
                $this->file->openOptionalFiles($option, $name);
            }
        }
    }
}
