<?php
namespace Esler\KivWeb;

class Container
{
    private $factories = [];

    private $instances = [];

    public function __construct(array $factories)
    {
        foreach ($factories as $id => $factory) {
            $this->addFactory($id, $factory);
        }
    }

    protected function addFactory(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        } elseif (isset($this->factories[$id])) {
            $factory = $this->factories[$id];
            return $this->instances[$id] = $factory();
        }

        throw new Exception\ServerError("Unknown service: " . $id);
    }
}
