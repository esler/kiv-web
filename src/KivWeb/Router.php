<?php
namespace Esler\KivWeb;

/**
 * This class is responsible for routing incoming requests
 */
class Router
{

    private $routes = [];

    public function addRoute(string $mapping, callable $handler): void
    {
        $this->routes[] = [$mapping, $handler];
    }

    public function dispatch(bool $enableMapping)
    {
        $request = Request::fromGlobals();

        foreach ($this->routes as list($mapping, $handler)) {
            $params   = $request->getQueryParams();
            $defaults = [];

            // converts mapping string to regex pattern
            // eg. /:control/:action~list -> ^/(?<control>[^/]+)/?(?<action>[^/]+)?$
            $pattern = '#^' . preg_replace_callback(
                '/:(\w+)(?:~(\w+))?/i',
                function ($matches) use (&$defaults) {
                    list(, $param, $default) = $matches + [null, null, null];

                    $defaults[$param] = $default;

                    $pattern = '(?<' . $param . '>[^/]+)';
                    return $default ? ('?' . $pattern . '?') : $pattern;
                },
                $mapping
            ) . '$#';

            if ($enableMapping) {
                // find match between request and a mapping
                if (preg_match($pattern, $request->getUri()->getPath(), $matches)) {
                    foreach ($defaults as $param => $default) {
                        $params[$param] = $matches[$param] ?? $default;
                    }

                    return $handler($request->withQueryParams($params));
                }
            } else {
                // find match between params of request and mapping
                $match = true && $defaults;
                foreach ($defaults as $param => $default) {
                    if ($value = $params[$param] ?? $default) {
                        $params[$param] = $value;
                    } else {
                        $match = false;
                    }
                }

                if ($match) {
                    return $handler($request->withQueryParams($params));
                }
            }
        }

        throw new Exception\NotFound('No route found for: ' . $request->getUri()->getPath());
    }
}
