<?php

namespace OpenOnMake;

use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\ArgvInput;

class CommandInput
{
    private ArgvInput $input;
    private Collection $collection;

    public function __construct(ArgvInput $input)
    {
        $this->input = $input;
        $this->setCollection();
    }

    public function getCollection() : Collection
    {
        return $this->collection;
    }

    private function setCollection()
    {
        $this->collection = collect(explode(' ', $this->input));
    }

    /**
     * First item in collection is comand string (index zero)
     * Second item is Class Name w/without namespace (index one)
     * Remaining items are just options
     *
     * @return void
     */
    public function getOptions() : array
    {
        return $this->collection->slice(2)->all();
    }

    public function getClassNameOfNameArgument() : string
    {
        $exploded = explode('\\', $this->collection[1]);
        $className = trim(array_pop($exploded), "'");
        return $className;
    }

    public function hasOptions() : bool
    {
        return $this->collection->count() > 2;
    }
}
