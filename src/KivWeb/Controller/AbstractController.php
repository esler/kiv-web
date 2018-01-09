<?php
namespace Esler\KivWeb\Controller;

use Esler\KivWeb\Container;
use Esler\KivWeb\Exception;
use Esler\KivWeb\Request;
use Esler\KivWeb\Utils;
use Esler\KivWeb\View;
use GuzzleHttp\Psr7\Response;
use function GuzzleHttp\Psr7\stream_for;

abstract class AbstractController
{
    private $request;
    private $container;
    private $response;
    private $view;

    /**
     * Constructor
     *
     * @param Container $container DI container
     * @param View      $view      a view
     */
    public function __construct(Container $container, View $view)
    {
        $this->container = $container;
        $this->view = $view;
    }

    /**
     * Dispaches given request - calls an action acording resolved params
     *
     * @param Request $request a request
     *
     * @return Response
     */
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
        $method  = 'action' . Utils::kebabCaseToCamelCase(ucfirst($action));

        // call controller action
        if (method_exists($this, $method)) {
            $this->getView()->setTemplate($control, $action);
            $this->$method();
        } else {
            throw new Exception\NotFound('Method ' . $method . ' not found in ' . static::class);
        }

        return $this->getResponse();
    }

    /**
     * Lookup to DI container
     *
     * @param string $id an ID
     *
     * @return mixed
     */
    protected function get(string $id)
    {
        return $this->container->get($id);
    }

    /**
     * Generates default context for view templates
     *
     * @return array
     */
    protected function getDefaultContext(): array
    {
        return ['request' => $this->getRequest(), 'me' => $this->get('me')];
    }

    /**
     * Returns current request
     *
     * @return Request
     */
    protected function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Returns current response
     *
     * @return Response
     */
    protected function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Returns a view
     *
     * @return View
     */
    protected function getView(): View
    {
        return $this->view;
    }

    /**
     * Setup reponse to a redirect
     *
     * @param string $uri URI to redirect
     *
     * @return void
     */
    protected function redirect(string $uri): void
    {
        $this->setResponse(
            $this->getResponse()
                ->withStatus(302)
                ->withHeader('Location', (string) $uri)
        );
    }

    /**
     * Render given context to view template
     *
     * @param array $context a context
     *
     * @return void
     */
    protected function render(array $context = []): void
    {
        $stream = stream_for($this->view->render($context + $this->getDefaultContext()));
        $this->setResponse($this->getResponse()->withBody($stream));
    }

    /**
     * Sets a response
     *
     * @param Response $response a response
     *
     * @return void
     */
    protected function setResponse(Response $response): void
    {
        $this->response = $response;
    }
}
