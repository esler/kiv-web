<?php
namespace Esler\KivWeb\Model;

use Esler\KivWeb\Db;
use Esler\KivWeb\Exception;
use Esler\KivWeb\Utils;

abstract class AbstractModel
{

    private $db;
    private $data = [];
    private $source = [];

    /**
     * Constructor
     *
     * @param array $source source data
     */
    public function __construct(array $source = [])
    {
        $this->init();
        foreach ($source as $property => $value) {
            $property = Utils::snakeCaseToCamelCase($property);
            $this->source[$property] = $value;
            $this->data[$property]   = $value;
        }
    }

    /**
     * Magic getter
     *
     * @param string $name name of property
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->getProperty($name);
    }

    /**
     * Magic is setter
     *
     * @param string $name name of property
     *
     * @return boolean
     */
    public function __isset(string $name)
    {
        return $this->issetProperty($name);
    }

    /**
     * Magic setter
     *
     * @param string $name  name of property
     * @param mixed  $value a value
     *
     * @return void
     */
    public function __set(string $name, $value)
    {
        $this->setProperty($name, $value);
    }

    /**
     * Magic unsetter
     *
     * @param string $name name of property
     *
     * @return void
     */
    public function __unset(string $name)
    {
        $this->setProperty($name, null);
    }

    /**
     * An init - useful for default values
     *
     * @return void
     */
    protected function init(): void
    {
    }

    /**
     * Is property set?
     *
     * @param string $property name of property
     *
     * @return boolean
     */
    protected function issetProperty(string $property): bool
    {
        return array_key_exists($property, $this->data);
    }

    /**
     * Get a property
     *
     * @param string $property name of property
     *
     * @return mixed
     */
    protected function getProperty(string $property)
    {
        if ($this->issetProperty($property)) {
            return $this->data[$property];
        }

        throw new Exception\InvalidArgument('Unknown property: ' . $property);
    }

    /**
     * Instance of DB
     *
     * @return Db
     */
    protected function db(): Db
    {
        return $this->db;
    }

    /**
     * Set instance of DB
     *
     * @param Db $db instace of Db
     *
     * @return void
     */
    public function setDb(Db $db): void
    {
        $this->db = $db;
    }

    /**
     * Sets a property
     *
     * @param string $property name of property
     * @param mixed  $value    a value
     *
     * @return void
     */
    protected function setProperty(string $property, $value)
    {
        return $this->data[$property] = $value;
    }

    /**
     * Export this model into array
     *
     * @param array $options an options
     *
     * @return array
     */
    public function toArray(array $options = []): array
    {
        $array = $this->data;

        if ($options['only_modified'] ?? false) {
            $array = array_diff_assoc($array, $this->source);
        }

        return $array;
    }
}
