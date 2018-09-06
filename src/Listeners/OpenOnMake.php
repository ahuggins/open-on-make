<?php

namespace OpenOnMake\Listeners;

class OpenOnMake
{
    protected $paths = [
        'channel' => 'app/Broadcasting/',
        'command' => 'app/Console/Commands/',
        'controller' => 'app/Http/Controllers/',
        'event' => 'app/Events/',
        'exception' => 'app/Exceptions/',
        'factory' => 'database/factories/',
        'job' => 'app/Jobs/',
        'listener' => 'app/Listeners/',
        'mail' => 'app/Mail/',
        'middleware' => 'app/Http/Middleware/',
        'migration' => '',
        'model' => 'app/',
        'notification' => 'app/Notifications/',
        'observer' => 'app/Observers/',
        'policy' => 'app/Policies/',
        'provider' => 'app/Providers/',
        'request' => 'app/Http/Requests/',
        'resource' => 'app/Http/Controllers/',
        'rule' => 'app/Rules/',
        'seeder' => 'database/seeds/',
        'feature' => 'tests/Feature/',
        'unit' => 'tests/Unit/',
        'widget' => 'app/Widgets/',
        'observer' => 'app/Observers/'
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
            $name = $command[1];
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
        $classType = str_replace('make:', '', $this->event->command);

        // special cases to handle
        if ($classType === 'auth') {
            return '';
        } elseif ($classType === 'test') {
            return base_path($this->getTestPath() . $this->filename());
        } elseif ($classType === 'migration') {
            return $this->getLatestMigrationFile();
        } else {
            if (! isset($this->paths[$classType])) {
                return $this->findFile();
            } else {
                return base_path($this->paths[$classType] . $this->filename());
            }
        }
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
    
        foreach ($finder as $file) {
            $path = $file->getRealPath();
            break;
        }
        
        return $path;
    }
}
