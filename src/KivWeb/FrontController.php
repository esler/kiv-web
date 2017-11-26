<?php
namespace Esler\KivWeb;

use Esler\KivWeb\Exception;
use GuzzleHttp\Psr7\ServerRequest as Request;
use GuzzleHttp\Psr7\Response;

class FrontController
{

    public static function dispatch(Request $request): void
    {
        if (empty($request->getQueryParams()['control'])) {
            throw new Exception\ServerError('No value for :control found');
        }

        // build classname, eg. Esler\KivWev\Controller\UsersController
        $classname = __NAMESPACE__ . '\\Controller\\' . ucfirst($request->getQueryParams()['control']);

        if (class_exists($classname)) {
            $controller = new $classname;
            self::render($controller->dispatch($request));
        } else {
            throw new Exception\NotFound('Controller ' . $classname . ' not found');
        }
    }

    public static function render(Response $response)
    {
        // Emit headers
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }

        // render body
        echo $response->getBody();
    }

}