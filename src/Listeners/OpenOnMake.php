<?php

namespace OpenOnMake\Listeners;

use OpenOnMake\File;
use OpenOnMake\Check;
use OpenOnMake\Paths;
use OpenOnMake\Options;
use OpenOnMake\Files\MigrationFile;

class OpenOnMake
{
    protected $event;
    protected $argName;
    protected $commandString;
    protected $help;

    public function __construct(File $file) {
        $this->file = $file;
    }

    public function handle($event)
    {
        if (Check::executedCommandWasMakeCommand($event->command)) {
            $this->setProperties($event);

            if (Check::commandIsOpenable($this->commandString, $this->help)) {
                $path = $this->determineFilePath();

                $this->file->open($path);

                $this->checkForFlags();
            }
        }
    }

    public function checkForFlags()
    {
        $command = collect(explode(' ', $this->event->input));

        if (Check::isMakeModelCommand($command[0])) {
            $exploded = explode('\\', $command[1]);
            $name = trim(array_pop($exploded), "'");

            if ($command->count() > 2) {
                // remove the `make:model` and $name so left with options only
                $command->shift();
                $command->shift();

                foreach ($command as $option) {
                    if (Options::exist($option)) {
                        $this->file->openFilesGeneratedInAdditionToModel($option, $name);
                    } elseif (Options::isAll($option)) {
                        $this->file->openAllTypes($name);
                    }
                }
            }
        }
    }

    public function determineFilePath()
    {
        if (Check::isTestCommand($this->commandString) || Check::isFactoryCommand($this->commandString)) {
            $name = $this->file->filename($this->commandString, $this->argName);
            $file = new \SplFileInfo($name);
            return $this->file->find($file);
        } elseif ($commandClass = Paths::getCommandClass($this->commandString)) {
            $reflection = new \ReflectionClass($commandClass);
            if (Check::isSubClassOfGeneratorCommand($reflection)) {
                $pathMethod = new \ReflectionMethod($commandClass, 'getPath');
                $pathMethod->setAccessible(true);

                $qualifyMethod = new \ReflectionMethod($commandClass, 'qualifyClass');
                $qualifyMethod->setAccessible(true);

                $instance = $this->getCommandInstance();

                $qualifiedName = $qualifyMethod->invokeArgs($instance, [$this->argName]);
                return $pathMethod->invokeArgs($instance, [$qualifiedName]);
            }
            // Migration is a special cases that do not extend the GeneratorComand. We can handle them like this:
            if ($reflection->getName() === Paths::getCommandPath('migration')) {
                return MigrationFile::getLatestMigrationFile();
            }
        }

        // last thing is to just try to find the name of file.
        return $this->file->find($this->file->filename($this->commandString, $this->argName));
    }

    protected function getCommandInstance()
    {
        $container = app();

        $instance = $container->make(Paths::getCommandClass($this->commandString));
        $instance->setLaravel($container);

        return $instance;
    }

    private function setProperties($event)
    {
        $this->event = $event;
        $this->commandString = $this->event->command;
        $this->argName = $this->event->input->getArgument('name');
        $this->help = $this->event->input->getOption('help');
    }
}
