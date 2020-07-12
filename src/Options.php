<?php

namespace OpenOnMake;

class Options
{
    private $options = [
        "-c" => "controller",
        "--controller" => "controller",
        "-f" => "factory",
        "--factory" => "factory",
        "-m" => "migration",
        "--migration" => "migration",
        "-r" => "resource",
        "--resource" => "resource"
    ];

    public function getOptions()
    {
        return $this->options;
    }

    public function getOption($option)
    {
        return $this->getOptions()[$option];
    }

    public function isResource($option)
    {
        return $option === '-r' || $option === '--resource' || $option === 'resource';
    }

    public function isMigration($option)
    {
        return $option === '-m' || $option === '--migration' || $option === 'migration';
    }

    public function isAll($option)
    {
        return $option === '-a' || $option === '--all';
    }
    
    public function exist($option)
    {
        return array_key_exists($option, $this->getOptions());
    }
}
