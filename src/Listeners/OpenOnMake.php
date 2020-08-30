<?php

namespace OpenOnMake\Listeners;


use OpenOnMake\OpenFile;
use OpenOnMake\CommandInfo;
use OpenOnMake\PathGetters\FilePath;
use OpenOnMake\File;

class OpenOnMake
{

    private $filePath;
    private $file;

    public function __construct(File $file, FilePath $filePath) {
        $this->filePath = $filePath;
        $this->file = $file;
    }

    public function handle($event)
    {
        $commandInfo = new CommandInfo(
            $event->command,
            $event->input->hasArgument('name') ? $event->input->getArgument('name') : null,
            $event->input,
            $event->output,
            $event->input->getOption('help')
        );
        if ($commandInfo->isOpenable()) {
            $path = $this->filePath->determine($commandInfo);

            OpenFile::open($path);

            $this->checkForFlags($commandInfo);
        }
    }

    public function checkForFlags($commandInfo)
    {
        if (! $commandInfo->isMakeModelCommand()) {
            return null;
        }

        $input = $commandInfo->getInput();

        if ($input->hasOptions()) {
            $name = $input->getClassNameOfNameArgument();
            foreach ($input->getOptions() as $option) {
                $this->file->openOptionalFiles($option, $name);
            }
        }
    }
}
