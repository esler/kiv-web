<?php
namespace Esler\KivWeb\Controller;

use Esler\KivWeb\Container;
use Esler\KivWeb\Exception;
use Esler\KivWeb\Request;
use Esler\KivWeb\View;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;

abstract class AbstractController
{
    private $request;
    private $container;
    private $response;
    private $view;

    public function __construct(Container $container, View $view)
    {
        $this->container = $container;
        $this->view = $view;
    }

    public function dispatch(Request $request): Response
    {
        if (!$request->getParam('action')) {
            throw new Exception\ServerError('No value for :action found');
        }

        $this->request  = $request;
        $this->response = new Response;

        // resolve action name
        $control = $request->getParam('control');
        $action  = $request->getParam('action');
        $method  = 'action' . ucfirst($action);

        // call controller action
        if (method_exists($this, $method)) {
            $this->getView()->setTemplate($control, $action);
            $this->$method();
        } else {
            throw new Exception\NotFound('Method ' . $method . ' not found in ' . static::class);
        }

        return $this->getResponse();
    }

    protected function get(string $id)
    {
        return $this->container->get($id);
    }

    protected function getRequest(): Request
    {
        return $this->request;
    }

    protected function getResponse(): Response
    {
        return $this->response;
    }

    protected function getView(): View
    {
        return $this->view;
    }

    protected function render(array $context): void
    {
        $stream = stream_for($this->view->render($context));
        $this->setResponse($this->getResponse()->withBody($stream));
    }

    protected function setResponse(Response $response): void
    {
        $this->response = $response;
    }
}
