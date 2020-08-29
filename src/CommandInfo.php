<?php

namespace OpenOnMake;

use OpenOnMake\Paths;
use OpenOnMake\CommandInput;

class CommandInfo
{
    /**
     * The command string from Artisan
     *
     * @var ?string
     */
    private ?string $commandString = null;
    /**
     * The name the command will use
     *
     * @var string
     */
    private ?string $argName;

    /**
     * If the command was run with --help
     * @var bool
     * */
    private bool $help = false;

    private $input;
    public $output;

    public function __construct($commandString = null, $argName = null, $input = null, $output = null, $help = false)
    {
        $this->commandString = $commandString;
        $this->argName = $argName;
        $this->help = $help;
        $this->output = $output;
        $this->filename = $this->ensurePHPExtension();
        $this->splFile = new \SplFileInfo($this->filename);
        $this->input = $input;
    }

    public function ensurePHPExtension()
    {
        if (!$this->argName) {
            return null;
        }

        $this->alertUserNotToAddPHPExtension();
        
        $name = str_replace('\\', '/', $this->argName);
        $name = str_replace('.php', '', $name);
        $name = $name . '.php';
        return $name;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getSplFile()
    {
        return $this->splFile;
    }

    public function getCommandString() : ?string
    {
        return $this->commandString;
    }

    public function getArgName() : ?string
    {
        return $this->argName;
    }

    public function getHelp() : bool
    {
        return $this->help;
    }

    public function isMakeCommand() : bool
    {
        return is_string($this->getCommandString()) && str_contains($this->getCommandString(), 'make:');
    }

    public function isMigrationCommand()
    {
        return is_string($this->getCommandString()) && str_contains($this->getCommandString(), ':migration');
    }

    /**
     * You can run artisan without a command..default to Lists
     *
     * @return boolean
     */
    public function isListCommand()
    {
        return $this->getCommandString() === null || $this->getCommandString() === 'list';
    }

    public function isCommandHelp() : bool
    {
        return $this->getHelp() == true;
    }

    public function isOpenable() : bool
    {
        if ($this->envProduction()) {
            return false;
        }

        if ($this->isListCommand()) {
            return false;
        }
        
        if ($this->isCommandHelp()) {
            return false;
        }
        
        return
            $this->hasName() &&
            $this->isMakeCommand() ;
    }

    public function envProduction() : bool
    {
        return config('app.env') == 'production';
    }

    /**
     * Fetched from `input` so that Openable does not access $argName before set.
     *
     * @return boolean
     */
    private function hasName()
    {
        return isset($this->argName);
    }

    /** This is because making a Model is only command you can generate other classes */
    public function isMakeModelCommand() : bool
    {
        return str_contains($this->getCommandString(), ':model');
    }

    public function isTestCommand() : bool
    {
        return str_replace('make:', '', $this->getCommandString()) === 'test';
    }

    public function isFactoryCommand() : bool
    {
        return str_replace('make:', '', $this->getCommandString()) === 'factory';
    }

    public function isCommandCommand() : bool
    {
        return str_replace('make:', '', $this->getCommandString()) === 'command';
    }

    private function getEventInput()
    {
        return $this->input;
    }

    public function getInput() : CommandInput
    {
        return new CommandInput($this->getEventInput());
    }

    public function getCommandClass()
    {
        $key = str_replace('make:', '', $this->getCommandString());
        if (!array_key_exists($key, Paths::$commands)) {
            return null;
        }
        return Paths::getCommandPath($key);
    }

    public function alertUserNotToAddPHPExtension()
    {
        if (is_string($this->argName) && str_contains($this->argName, '.php')) {
            $this->output->writeln('<comment>Open-On-Make: </comment><error>You should not provide .php</error>');
            $this->output->writeln('<comment>Open-On-Make: </comment><error>When user provides `.php` extension, this results in a file with `.php.php` as extension.</error>');
            $this->output->writeln('<comment>Open-On-Make: </comment><error>Open-On-Make can not open file.</error>');
            return;
        }
    }
}
