<?php
namespace Esler\KivWeb\Model;

abstract class AbstractModel
{

    public function __get(string $name)
    {
        if ($this->__isset($name)) {
            return $this->$name;
        }

        throw new InvalidArgumentException('Unknown property: ' . $name);
    }

    public function __isset($name)
    {
        return property_exists($this, $name);
    }

    public function __set(string $name, $value)
    {
        if ($this->__isset($name)) {
            $this->$name = $value;
        }

        throw new InvalidArgumentException('Cannot setup public property: ' . $name);
    }

    public static function fromArray(array $array)
    {
        $model = new static;

        foreach ($array as $property => $value) {
            $model->$property = $value;
        }

        return $model;
    }

    public function toArray(array $array = null): array
    {
        $array = $array ?? array_filter(get_object_vars($this));

        return array_map(function ($value) {
            if ($value instanceof Model) {
                return $value->toArray();
            } elseif (is_array($value)) {
                return $this->toArray($value);
            } else {
                return $value;
            }
        }, $array);
    }
}
