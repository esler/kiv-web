<?php
namespace Esler\KivWeb;

use Closure;

class Container
{
    private $factories = [];
    private $instances = [];

    /**
     * Constructor
     *
     * @param array $factories factories for creating singletons
     */
    public function __construct(array $factories)
    {
        foreach ($factories as $id => $factory) {
            $this->addFactory($id, $factory);
        }
    }

    /**
     * Adds a factory
     *
     * @param string  $id      an ID
     * @param Closure $factory a factory
     */
    protected function addFactory(string $id, Closure $factory): void
    {
        $this->factories[$id] = $factory;
    }

    /**
     * Returns a singleton by given ID
     *
     * @param string $id an ID
     *
     * @return mixed
     */
    public function get(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        } elseif (isset($this->factories[$id])) {
            $factory = $this->factories[$id];
            return $this->instances[$id] = $factory->call($this);
        }

        throw new Exception\ServerError("Unknown service: " . $id);
    }
}
