<?php

namespace OpenOnMake;

use OpenOnMake\Check;
use OpenOnMake\Paths;
use OpenOnMake\Options;
use OpenOnMake\CommandInfo;
use Symfony\Component\Finder\Finder;
use OpenOnMake\Openers\MigrationFile;

class File
{
    private $options;
    private $open;
    private $finder;
    
    public function __construct(OpenFile $open, Options $options, Finder $finder)
    {
        $this->open = $open;
        $this->options = $options;
        $this->finder = $finder;
    }

    public function openOptionalFiles($option, $name)
    {
        if ($this->options->exist($option)) {
            $this->openFilesGeneratedInAdditionToModel($option, $name);
        } elseif ($this->options->isAll($option)) {
            $this->openAllTypes($name);
        }
    }

    public function open($path)
    {
        $this->open->open($path);
    }

    public function getFileName($name)
    {
        return str_replace('\\', '/', $name . '.php');
    }

    public function find(CommandInfo $commandInfo)
    {
        $this->finder->files()->depth('>= 0')->depth('< 10')->name($commandInfo->getSplFile()->getFileName())->in(base_path());
        
        $path = '';

        if ($this->finder->hasResults()) {
            foreach ($this->finder as $file) {
                $path = $file->getRealPath();
                break;
            }
        }

        return $path;
    }

    public function openAdditionalFile($path, $name, $option)
    {
        $this->open->open($path . $name . ucfirst($this->options->getOption($option)) . '.php');
    }

    public function openAllTypes($name)
    {
        foreach ($this->options->getOptions() as $key => $value) {
            if (! $this->options->isMigration($value) && ! $this->options->isResource($value)) {
                $this->openAdditionalFile(Paths::getPath($value), $name, $key);
            } elseif ($this->options->isMigration($value)) {
                MigrationFile::open();
            }
        }
    }

    public function openFilesGeneratedInAdditionToModel($option, $name)
    {
        if ($this->options->isMigration($option)) {
            MigrationFile::open();
        } else {
            $option = $this->options->isResource($option) ? '-c' : $option;
            $this->openAdditionalFile(Paths::getPath($this->options->getOption($option)), $name, $option);
        }
    }
}
