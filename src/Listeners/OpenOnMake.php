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
        'resource' => 'app/Http/Resources/',
        'rule' => 'app/Rules/',
        'seeder' => 'database/seeds/',
        'feature' => 'tests/Feature/',
        'unit' => 'tests/Unit/',
        'widget' => 'app/Widgets/',
        'observer' => 'app/Observers/'
    ];

    public function handle($event)
    {
        $this->paths = array_merge($this->paths, config('open-on-make.paths'));

        if ($this->envNotProduction() && $this->executedCommandWasMakeCommand($event)) {
            $classType = str_replace('make:', '', $event->command);

            // special cases to handle
            if ($classType === 'auth') {
                return;
            } elseif ($classType === 'test') {
                $path = base_path($this->getTestPath($event) . $this->filename($event));
            } elseif ($classType === 'migration') {
                $path = $this->getLatestMigrationFile();
            } else {
                $path = base_path($this->paths[$classType] . $this->filename($event));
            }

            exec(
                config('open-on-make.editor') . ' ' .
                config('open-on-make.flags') . ' ' .
                escapeshellcmd($path)
            );
        }
    }

    public function executedCommandWasMakeCommand($event)
    {
        return str_contains($event->command, 'make:');
    }

    public function envNotProduction()
    {
        return config('app.env') !== 'production';
    }

    public function filename($event)
    {
        return $event->input->getArgument('name') . '.php';
    }

    public function getTestPath($event)
    {
        $isUnit = $event->input->getOption('unit');
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
}
