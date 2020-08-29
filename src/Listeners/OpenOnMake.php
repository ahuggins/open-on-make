<?php

namespace OpenOnMake\Listeners;

use OpenOnMake\OpenFile;
use OpenOnMake\CommandInfo;

class OpenOnMake
{
    private $commandInfo;

    public function handle($event)
    {
        $this->commandInfo = new CommandInfo(
            $event->command,
            $event->input->hasArgument('name') ? $event->input->getArgument('name') : null,
            $event->input,
            $event->output,
            $event->input->getOption('help')
        );
        if ($this->commandInfo->isOpenable()) {
            $path = $this->filePath->determine($this->commandInfo);
            
            OpenFile::open($path);
            
            $this->checkForFlags();
        }
    }

    public function checkForFlags()
    {
        if (! $this->commandInfo->isMakeModelCommand()) {
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
