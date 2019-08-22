<?php

namespace OpenOnMake\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'open:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Easily install correct env variable for Open On Make';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected $editors = [
        'Sublime' => 'subl',
        'VSCode' => 'code',
        'Atom' => 'atom',
        'PHPStorm' => 'pstorm',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $choice = $this->choice('What editor do you want to use?', array_keys($this->editors));

        Artisan::call('env:set', [
            'key' => 'OPEN_ON_MAKE_EDITOR',
            'value' => $this->editors[$choice],
        ]);

        $this->info('Your OPEN_ON_MAKE_EDITOR has been set to ' . $choice);
    }
}
