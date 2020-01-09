<?php

namespace OpenOnMake;

use OpenOnMake\Check;
use OpenOnMake\Paths;
use OpenOnMake\Files\MigrationFile;

class File
{
    public static function open($path)
    {
        exec(
            config('open-on-make.editor') . ' ' .
            config('open-on-make.flags') . ' ' .
            escapeshellarg($path)
        );
    }

    public static function openLatestMigration()
    {
        self::open(MigrationFile::getLatestMigrationFile());
    }

    public static function getViewFileName($name)
    {
        $pathSeparator = str_replace('.', '/', $name) . '.blade.php';
        $parts = explode('/', $pathSeparator);
        return array_pop($parts);
    }

    public static function getFileName($name)
    {
        return str_replace('\\', '/', $name . '.php');
    }

    public static function filename($commandString, $name)
    {
        if (Check::isViewCommand($commandString)) {
            return self::getViewFileName($name);
        }

        return self::getFileName($name);
    }

    public static function find($filename)
    {
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

    public static function openAdditionalFile($path, $name, $option)
    {
        File::open($path . $name . ucfirst(Options::getOption($option)) . '.php');
    }

    public static function openAllTypes($name)
    {
        foreach (Options::getOptions() as $key => $value) {
            if (! Options::isMigration($value) && ! Options::isResource($value)) {
                File::openAdditionalFile(Paths::getPath($value), $name, $key);
            } elseif (Options::isMigration($value)) {
                File::openLatestMigration();
            }
        }
    }

    public static function openFilesGeneratedInAdditionToModel($option, $name)
    {
        if (Options::isMigration($option)) {
            File::openLatestMigration();
        } else {
            $option = Options::isResource($option) ? '-c' : $option;
            File::openAdditionalFile(Paths::getPath(Options::getOption($option)), $name, $option);
        }
    }
}
