<?php
namespace Esler\KivWeb;

class Utils
{

    public static function camelCaseToSnakeCase(string $camelCase): string
    {
        $callback = function ($matches) {
            return '_' . $matches[0];
        };

        return strtolower(preg_replace_callback('/(?<!^)\p{Lu}/', $callback, $camelCase));
    }

    public static function snakeCaseToCamelCase(string $snakeCase): string
    {
        $callback = function ($matches) {
            return strtoupper($matches[0][1]);
        };

        return preg_replace_callback('/_\w/', $callback, $snakeCase);
    }

    public static function getTypeOrClass($value): string
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }

    public static function kebabCaseToCamelCase(string $kebabCase): string
    {
        $callback = function ($matches) {
            return strtoupper($matches[0][1]);
        };

        return preg_replace_callback('/-\w/', $callback, $kebabCase);
    }
}
