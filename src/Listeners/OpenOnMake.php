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
        'policy' => 'app/Policies/',
        'provider' => 'app/Providers/',
        'request' => 'app/Http/Requests/',
        'resource' => 'app/Http/Resources/',
        'rule' => 'app/Rules/',
        'seeder' => 'database/seeds/',
        'feature' => 'tests/Feature/',
        'unit' => 'tests/Unit/'
    ];

    public function handle($event)
    {
        // dd($event);
        if (
            config('app.env') !== 'production' 
            && str_contains($event->command, 'make:')
        ) {
            $classType = explode('make:', $event->command)[1];
            // make:auth is not really something that works here
            // it's not generating a single class
            // So just return early and move on
            if ($classType == 'auth') {
                return;
            }

            if ($classType == 'test') {
                if ($event->input->getOption('unit')) {
                    $path = $this->paths['unit'] . $event->input->getArgument('name') . '.php';
                } else {
                    $path = $this->paths['feature'] . $event->input->getArgument('name') . '.php';
                }
            } elseif ($classType == 'migration') {
                $path = $this->getLatestMigrationFile();
            } else {
                $path = base_path(
                    $this->paths[$classType] . $event->input->getArgument('name') . '.php'
                );
            }

            // open the file
            // This may need to be customized to your system...PHPStorm or 
            // might be subl instead of sublime depending on your system.
            // 
            
            exec(
                config('open-on-make.editor') . ' ' . 
                config('open-on-make.flags') . ' ' . 
                escapeshellcmd($path)
            );
            // uncomment for PHPStorm
            // exec('pstorm ' . escapeshellcmd($path));
            // exec('atom ' . escapeshellcmd($path));
        }
    }

    protected function getLatestMigrationFile()
    {
        app()->config["filesystems.disks.easyOpen"] = [
            'driver' => 'local',
            'root' => base_path(),
        ];

        $newestMigration = collect(
            \Storage::disk('easyOpen')->files('database/migrations')
        )->pop();

        unset(app()->config['filesystems.disks.easyOpen']);

        return base_path(
            $newestMigration
        );
    }
}
