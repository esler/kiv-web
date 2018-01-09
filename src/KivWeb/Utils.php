<?php
namespace Esler\KivWeb;

class Utils
{

    /**
     * Returns given camel case as snake case notation
     *
     * @param string $camelCase came case string
     *
     * @return string
     */
    public static function camelCaseToSnakeCase(string $camelCase): string
    {
        $callback = function ($matches) {
            return '_' . $matches[0];
        };

        return strtolower(preg_replace_callback('/(?<!^)\p{Lu}/', $callback, $camelCase));
    }

    /**
     * Returns snake case as camel case notation
     *
     * @param string $snakeCase snake case string
     *
     * @return string
     */
    public static function snakeCaseToCamelCase(string $snakeCase): string
    {
        $callback = function ($matches) {
            return strtoupper($matches[0][1]);
        };

        return preg_replace_callback('/_\w/', $callback, $snakeCase);
    }

    /**
     * Returns type of scalar or name of the class for objects
     *
     * @param mixed $value scalar or object
     *
     * @return string
     */
    public static function getTypeOrClass($value): string
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }

    /**
     * Returns kebab case as camel case
     *
     * @param string $kebabCase kebab case string
     *
     * @return string
     */
    public static function kebabCaseToCamelCase(string $kebabCase): string
    {
        $callback = function ($matches) {
            return strtoupper($matches[0][1]);
        };

        return preg_replace_callback('/-\w/', $callback, $kebabCase);
    }
}
