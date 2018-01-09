<?php
namespace Esler\KivWeb\Utils;

use Generator;

class Enumerable
{
    private $source;

    public function __construct(Generator $source)
    {
        $this->source = $source;
    }

    public function map(callable $callback)
    {
        return new Enumerable($this->doMap($callback));
    }

    public function select(callable $callback)
    {
        return new Enumerable($this->doSelect($callback));
    }

    private function doMap(callable $callback)
    {
        foreach ($this->source as $key => $value) {
            echo __METHOD__.PHP_EOL;
            yield $key => $callback($value, $key);
        }
    }

    private function doSelect(callable $callback)
    {
        foreach ($this->source as $key => $value) {
            echo __METHOD__.PHP_EOL;
            if ($callback($value, $key)) {
                yield $key => $value;
            }
        }
    }

    public function toArray(): array
    {
        return iterator_to_array($this->source);
    }
}
