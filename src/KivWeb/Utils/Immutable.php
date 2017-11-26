<?php
namespace Esler\KivWeb\Utils;

trait Immutable
{

    protected function withProperty(string $property, $value)
    {
        $new = clone $this;
        $new->$property = $value;
        return $new;
    }
}
