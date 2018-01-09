<?php
namespace Esler\KivWeb;

use Esler\KivWeb\Exception;
use GuzzleHttp\Psr7\ServerRequest as Request;
use GuzzleHttp\Psr7\Response;
use Twig_Environment;

class FrontController
{
    private $container;
    private $view;

    public function __construct(Container $container, View $view)
    {
        $this->container = $container;
        $this->view = $view;
    }

    public function __invoke(Request $request): void
    {
        $this->dispatch($request);
    }

    public function dispatch(Request $request): void
    {
        if (empty($request->getQueryParams()['control'])) {
            throw new Exception\ServerError('No value for :control found');
        }

        // build classname, eg. Esler\KivWev\Controller\UsersController
        $control = Utils::kebabCaseToCamelCase($request->getQueryParams()['control']);
        $classname = __NAMESPACE__ . '\\Controller\\' . ucfirst($control);

        if (class_exists($classname)) {
            $controller = new $classname($this->container, $this->view);
            $this->render($controller->dispatch($request));
        } else {
            throw new Exception\NotFound('Controller ' . $classname . ' not found');
        }
    }

    protected function get(string $id)
    {
        return $this->container->get($id);
    }

    public function render(Response $response)
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
