<?php

namespace OpenOnMake\Listeners;

use Illuminate\Foundation\Console as FoundationConsole;
use Illuminate\Routing\Console as RoutingConsole;
use Illuminate\Database\Console as DatabaseConsole;

class OpenOnMake
{
    protected $paths = [
        'controller' => 'app/Http/Controllers/',
        'factory' => 'database/factories/',
        'migration' => '',
        'resource' => 'app/Http/Controllers/',
    ];

    protected $commands = [
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

    protected $options = [
        "-c" => "controller",
        "--controller" => "controller",
        "-f" => "factory",
        "--factory" => "factory",
        "-m" => "migration",
        "--migration" => "migration",
        "-r" => "resource",
        "--resource" => "resource"
    ];

    protected $event;

    public function handle($event)
    {
        $this->event = $event;
        $this->paths = array_merge($this->paths, config('open-on-make.paths'));

        if ($this->commandIsOpenable()) {
            $path = $this->determineFilePath();

            $this->openFile($path);

            $this->checkForFlags();
        }
    }

    public function checkForFlags()
    {
        $command = explode(' ', $this->event->input);

        if ($this->isMakeModelCommand($command)) {
            $exploded = explode('\\', $command[1]);
            $name = trim(array_pop($exploded), "'");

            if (count($command) > 2) {
                // remove the `make:model` and $name so left with options only
                array_shift($command);
                array_shift($command);

                foreach ($command as $option) {
                    if (array_key_exists($option, $this->options)) {
                        $this->openFilesGeneratedInAdditionToModel($option, $name);
                    } elseif ($option == '-a' || $option == '--all') {
                        $this->openAllTypes($name);
                    }
                }
            }
        }
    }

    public function openFilesGeneratedInAdditionToModel($option, $name)
    {
        if ($option === '-r' || $option === '--resource') {
            $this->openAdditionalFile($this->paths[$this->options[$option]], $name, '-c');
        } elseif ($option === '-m' || $option === '--migration') {
            $this->openFile($this->getLatestMigrationFile());
        } else {
            $this->openAdditionalFile($this->paths[$this->options[$option]], $name, $option);
        }
    }

    /** This is because making a Model is only command you can generate other classes */
    public function isMakeModelCommand($command)
    {
        return str_contains($command[0], ':model');
    }

    public function openAllTypes($name)
    {
        foreach ($this->options as $key => $value) {
            if ($value !== 'migration' && $value !== 'resource') {
                $this->openAdditionalFile($this->paths[$value], $name, $key);
            } elseif ($value === 'migration') {
                $this->openFile($this->getLatestMigrationFile());
            }
        }
    }

    public function openAdditionalFile($path, $name, $option)
    {
        $this->openFile($path . $name . ucfirst($this->options[$option]) . '.php');
    }

    public function commandIsOpenable()
    {
        return $this->envNotProduction() &&
            $this->executedCommandWasMakeCommand() &&
            $this->event->input->getOption('help') !== true;
    }

    public function determineFilePath()
    {
        if ($commandClass = $this->getCommandClass()) {
            $reflection = new \ReflectionClass($commandClass);
            if ($this->isSubClassOfGeneratorCommand($reflection)) {
                $pathMethod = new \ReflectionMethod($commandClass, 'getPath');
                $pathMethod->setAccessible(true);
    
                $qualifyMethod = new \ReflectionMethod($commandClass, 'qualifyClass');
                $qualifyMethod->setAccessible(true);
    
                $instance = $this->getCommandInstance();
    
                $qualifiedName = $qualifyMethod->invokeArgs($instance, [$this->event->input->getArgument('name')]);
                return $pathMethod->invokeArgs($instance, [$qualifiedName]);
            }
            // Migration is a special cases that do not extend the GeneratorComand. We can handle them like this:
            if ($reflection->getName() === $this->commands['migration']) {
                return $this->getLatestMigrationFile();
            }
        }
        
        // last thing is to just try to find the name of file.
        return $this->findFile();
    }

    protected function isSubClassOfGeneratorCommand($reflection)
    {
        return $reflection->getParentClass()->getName() === \Illuminate\Console\GeneratorCommand::class;
    }

    protected function getCommandInstance()
    {
        $container = app();

        $instance = $container->make($this->getCommandClass());
        $instance->setLaravel($container);

        return $instance;
    }

    protected function getCommandClass()
    {
        $command = str_replace('make:', '', $this->event->command);
        
        return $this->commands[$command] ?? null;
    }

    public function determineFileType()
    {
        return str_replace('make:', '', $this->event->command);
    }

    public function openFile($path)
    {
        exec(
            config('open-on-make.editor') . ' ' .
            config('open-on-make.flags') . ' ' .
            escapeshellarg($path)
        );
    }

    public function executedCommandWasMakeCommand()
    {
        return str_contains($this->event->command, 'make:');
    }

    public function envNotProduction()
    {
        return config('app.env') !== 'production';
    }

    public function filename()
    {
        if ($this->determineFileType() == 'view') {
            $pathSeparator = str_replace('.', '/', $this->event->input->getArgument('name')) . '.blade.php';
            $parts = explode('/', $pathSeparator);
            return array_pop($parts);
        }

        return str_replace('\\', '/', $this->event->input->getArgument('name') . '.php');
    }

    public function getTestPath()
    {
        $isUnit = $this->event->input->getOption('unit');
        return $isUnit ? $this->paths['unit'] : $this->paths['feature'];
    }

    protected function getLatestMigrationFile()
    {
        $this->createDiskForAppRoot();

        $newestMigration = collect(
            \Storage::disk('easyOpen')->files('database/migrations')
        )->pop();

        $this->unsetDiskForAppRoot();

        return base_path($newestMigration);
    }

    public function unsetDiskForAppRoot()
    {
        unset(app()->config['filesystems.disks.easyOpen']);
    }

    public function createDiskForAppRoot()
    {
        app()->config["filesystems.disks.easyOpen"] = [
            'driver' => 'local',
            'root' => base_path(),
        ];
    }

    public function findFile()
    {
        $finder = new \Symfony\Component\Finder\Finder();
        $finder->files()->name($this->filename($this->event))->in(base_path());

        $path = '';

        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $path = $file->getRealPath();
                break;
            }
        }

        return $path;
    }
}
