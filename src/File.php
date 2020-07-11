<?php

namespace OpenOnMake;

use OpenOnMake\Check;
use OpenOnMake\Paths;
use OpenOnMake\Files\MigrationFile;

class File
{
    public function __construct(OpenFile $open) {
        $this->open = $open;
    }

    public function open($path)
    {
        $this->open->open($path);
    }

    public function openLatestMigration()
    {
        $this->open->open(MigrationFile::getLatestMigrationFile());
    }

    public function getViewFileName($name)
    {
        $pathSeparator = str_replace('.', '/', $name) . '.blade.php';
        $parts = explode('/', $pathSeparator);
        return array_pop($parts);
    }

    public function getFileName($name)
    {
        return str_replace('\\', '/', $name . '.php');
    }

    public function filename($commandString, $name)
    {
        if (Check::isViewCommand($commandString)) {
            return $this->getViewFileName($name);
        }

        return $this->getFileName($name);
    }

    public function find($filename)
    {
        $filename = $this->getFileNameFromSplFileInfo($filename);

        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->name($filename)->in(base_path());

        $path = '';

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $path = $file->getRealPath();
                break;
            }
        }

        return $path;
    }

    public function openAdditionalFile($path, $name, $option)
    {
        $this->open->open($path . $name . ucfirst(Options::getOption($option)) . '.php');
    }

    public function openAllTypes($name)
    {
        foreach (Options::getOptions() as $key => $value) {
            if (! Options::isMigration($value) && ! Options::isResource($value)) {
                $this->openAdditionalFile(Paths::getPath($value), $name, $key);
            } elseif (Options::isMigration($value)) {
                $this->openLatestMigration();
            }
        }
    }

    public function openFilesGeneratedInAdditionToModel($option, $name)
    {
        if (Options::isMigration($option)) {
            $this->openLatestMigration();
        } else {
            $option = Options::isResource($option) ? '-c' : $option;
            $this->openAdditionalFile(Paths::getPath(Options::getOption($option)), $name, $option);
        }
    }

    public function getFileNameFromSplFileInfo($filename)
    {
        if ($filename instanceof \SplFileInfo) {
            return $filename->getFileName();
        }
        return $filename;
    }
}
