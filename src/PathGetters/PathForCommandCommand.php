<?php

namespace OpenOnMake\PathGetters;

use OpenOnMake\Check;
use OpenOnMake\Paths;
use OpenOnMake\CommandInfo;
use OpenOnMake\Files\MigrationFile;

class PathForCommandCommand
{
    public function handle(CommandInfo $commandInfo)
    {
        $commandClass = $commandInfo->getCommandClass();
        $reflection = new \ReflectionClass($commandClass);
        if (Check::isSubClassOfGeneratorCommand($reflection)) {
            $pathMethod = $this->setGetPathToPublic($commandClass);
            $qualifyMethod = $this->setQualifyClassToPublic($commandClass);

            $instance = $this->getCommandInstance($commandInfo);

            $qualifiedName = $qualifyMethod->invokeArgs($instance, [$commandInfo->getArgName()]);
            return $pathMethod->invokeArgs($instance, [$qualifiedName]);
        }
    }

    private function getCommandInstance($commandInfo)
    {
        $container = app();

        $instance = $container->make($commandInfo->getCommandClass());
        $instance->setLaravel($container);

        return $instance;
    }

    public function setGetPathToPublic($commandClass)
    {
        $pathMethod = new \ReflectionMethod($commandClass, 'getPath');
        $pathMethod->setAccessible(true);
        return $pathMethod;
    }

    public function setQualifyClassToPublic($commandClass)
    {
        $qualifyMethod = new \ReflectionMethod($commandClass, 'qualifyClass');
        $qualifyMethod->setAccessible(true);
        return $qualifyMethod;
    }
}
