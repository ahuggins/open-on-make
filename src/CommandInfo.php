<?php

namespace OpenOnMake;

use OpenOnMake\Check;
use OpenOnMake\Paths;
use Illuminate\Console\Events\CommandFinished;

class CommandInfo
{
    /**
     * The full event executed by Artisan
     *
     * @var CommandFinished
     */
    private $rawEvent;

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

    public function __construct($event)
    {
        $this->rawEvent = $event;
        $this->commandString = $this->rawEvent->command;
        if ($this->isOpenable()) {
            $this->argName = $this->rawEvent->input->getArgument('name');
            $this->help = $this->rawEvent->input->getOption('help');
            $this->filename = $this->ensurePHPExtension();
            $this->splFile = new \SplFileInfo($this->filename);
        }
    }

    public function ensurePHPExtension()
    {
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

    public function getEvent() : CommandFinished
    {
        return $this->rawEvent;
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
        return str_contains($this->getCommandString(), 'make:');
    }

    public function isMigrationCommand()
    {
        return str_contains($this->getCommandString(), ':migration');
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

    public function notCommandHelp() : bool
    {
        return $this->getHelp() !== true;
    }

    public function isOpenable() : bool
    {
        return Check::envNotProduction() &&
            $this->hasName() &&
            ! $this->isListCommand() &&
            $this->isMakeCommand() &&
            $this->notCommandHelp();
    }

    /**
     * Fetched from `input` so that Openable does not access $argName before set.
     *
     * @return boolean
     */
    private function hasName()
    {
        return isset($this->rawEvent->input->getArguments()['name']);
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
        return $this->rawEvent->input;
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
        if (str_contains($this->argName, '.php')) {
            $this->rawEvent->output->writeln('<comment>Open-On-Make: </comment><error>You should not provide .php</error>');
            $this->rawEvent->output->writeln('<comment>Open-On-Make: </comment><error>When user provides `.php` extension, this results in a file with `.php.php` as extension.</error>');
            $this->rawEvent->output->writeln('<comment>Open-On-Make: </comment><error>Open-On-Make can not open file.</error>');
            exit;
        }
    }
}
