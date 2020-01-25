<?php

namespace OpenOnMake\Testing;

class IsGenerator extends \Illuminate\Console\GeneratorCommand {
    protected $signature = 'make:onlyClassExists {name : The name of the migration}';

    public function handle()
    {
        
    }

    public function getStub()
    {
        # code...
    }
}