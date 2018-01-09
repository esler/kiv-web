<?php
namespace Esler\KivWeb\Utils;

trait Immutable
{

    /**
     * Creates clone of current instance and set its property
     *
     * @param string $property name of property
     * @param mixed  $value    a value
     *
     * @return self
     */
    protected function withProperty(string $property, $value)
    {
        $new = clone $this;
        $new->$property = $value;
        return $new;
    }
}
