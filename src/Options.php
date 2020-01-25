<?php

namespace OpenOnMake;

class Options
{
    public static $options = [
        "-c" => "controller",
        "--controller" => "controller",
        "-f" => "factory",
        "--factory" => "factory",
        "-m" => "migration",
        "--migration" => "migration",
        "-r" => "resource",
        "--resource" => "resource"
    ];

    public static function getOptions()
    {
        return self::$options;
    }

    public static function getOption($option)
    {
        return self::getOptions()[$option];
    }

    public static function isResource($option)
    {
        return $option === '-r' || $option === '--resource' || $option === 'resource';
    }

    public static function isMigration($option)
    {
        return $option === '-m' || $option === '--migration' || $option === 'migration';
    }

    public static function isAll($option)
    {
        return $option === '-a' || $option === '--all';
    }
    
    public static function exist($option)
    {
        return array_key_exists($option, self::getOptions());
    }
}
