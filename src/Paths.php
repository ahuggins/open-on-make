<?php

namespace OpenOnMake;

use Illuminate\Routing\Console as RoutingConsole;
use Illuminate\Database\Console as DatabaseConsole;
use Illuminate\Foundation\Console as FoundationConsole;
use OpenOnMake\Exceptions\UnsupportedCommandType;

class Paths
{
    public static $paths = [
        'controller' => 'app/Http/Controllers/',
        'factory' => 'database/factories/',
        'migration' => '',
        'resource' => 'app/Http/Controllers/',
    ];

    public static $commands = [
        'action' => \Spatie\QueueableAction\ActionMakeCommand::class,
        'channel' => FoundationConsole\ChannelMakeCommand::class,
        'command' => FoundationConsole\ConsoleMakeCommand::class,
        'controller' => RoutingConsole\ControllerMakeCommand::class,
        'event' => FoundationConsole\EventMakeCommand::class,
        'exception' => FoundationConsole\ExceptionMakeCommand::class,
        'factory' => DatabaseConsole\Factories\FactoryMakeCommand::class,
        'job' => FoundationConsole\JobMakeCommand::class,
        'listener' => FoundationConsole\ListenerMakeCommand::class,
        'mail' => FoundationConsole\MailMakeCommand::class,
        'middleware' => RoutingConsole\MiddlewareMakeCommand::class,
        'migration' => DatabaseConsole\Migrations\MigrateMakeCommand::class,
        'model' => FoundationConsole\ModelMakeCommand::class,
        'notification' => FoundationConsole\NotificationMakeCommand::class,
        'observer' => FoundationConsole\ObserverMakeCommand::class,
        'policy' => FoundationConsole\PolicyMakeCommand::class,
        'provider' => FoundationConsole\ProviderMakeCommand::class,
        'request' => FoundationConsole\RequestMakeCommand::class,
        'resource' => FoundationConsole\ResourceMakeCommand::class,
        'rule' => FoundationConsole\RuleMakeCommand::class,
        'seeder' => DatabaseConsole\Seeds\SeederMakeCommand::class,
        'test' => FoundationConsole\TestMakeCommand::class,
        'widget' => \Arrilot\Widgets\Console\WidgetMakeCommand::class,
        'observer' => FoundationConsole\ObserverMakeCommand::class,
        'view' => \Sven\ArtisanView\Commands\MakeView::class,
    ];

    public static function getPaths()
    {
        return array_merge(self::$paths, config('open-on-make.paths'));
    }

    public static function getCommandClass(string $commandString)
    {
        $key = str_replace('make:', '', $commandString);
        if (!array_key_exists($key, self::$commands)) {
            return null;
        }
        return self::getCommandPath($key);
    }

    public static function getPath(string $key) : string
    {
        return self::getPaths()[$key];
    }

    public static function getCommandPath(string $key) : string
    {
        if (! isset(self::$commands[$key])) {
            throw new UnsupportedCommandType(sprintf('Unknown Make Command: %s', $key));
        }
        return self::$commands[$key];
    }
}
