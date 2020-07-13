<?php

namespace OpenOnMake\PathGetters;

use OpenOnMake\Check;
use OpenOnMake\Paths;
use OpenOnMake\CommandInfo;
use OpenOnMake\Files\MigrationFile;

class PathForCommandCommand extends AbstractPath
{
    public function handle(CommandInfo $commandInfo)
    {
        $commandClass = $commandInfo->getCommandClass();
        $reflection = new \ReflectionClass($commandClass);
        if (Check::isSubClassOfGeneratorCommand($reflection)) {
            $pathMethod = $this->setGetPathToPublic($commandClass);
            $qualifyMethod = $this->setQualifyClassToPublic($commandClass);

            $instance = $this->getCommandInstance($commandInfo);

            $qualifiedName = $qualifyMethod->invokeArgs($instance, [$this->argName]);
            return $pathMethod->invokeArgs($instance, [$qualifiedName]);
        }
        // Migration is a special cases that do not extend the GeneratorComand. We can handle them like this:
        if ($reflection->getName() === Paths::getCommandPath('migration')) {
            return MigrationFile::getLatestMigrationFile();
        }
    }

    private function getCommandInstance($commandInfo)
    {
        $container = app();

        $instance = $container->make(Paths::getCommandClass($commandInfo->getCommandString()));
        $instance->setLaravel($container);

        return $instance;
    }
}
