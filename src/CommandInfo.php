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
    private string $argName;

    /**
     * If the command was run with --help
     * @var bool
     * */
    private bool $help = false;

    public function __construct($event)
    {
        $this->rawEvent = $event;
        $this->commandString = $this->rawEvent->command;
        if ($this->commandString) {
            $this->argName = $this->rawEvent->input->getArgument('name');
            $this->help = $this->rawEvent->input->getOption('help');
        }
    }

    public function getEvent() : CommandFinished
    {
        return $this->rawEvent;
    }

    public function getCommandString() : ?string
    {
        return $this->commandString;
    }

    public function getArgName() : string
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

    /**
     * You can run artisan without a command..default to Lists
     *
     * @return boolean
     */
    public function isListCommand()
    {
        return $this->getCommandString() === null;
    }

    public function notCommandHelp() : bool
    {
        return $this->getHelp() !== true;
    }

    public function isOpenable() : bool
    {
        return Check::envNotProduction() &&
            !$this->isListCommand() &&
            $this->isMakeCommand() &&
            $this->notCommandHelp();
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
}
