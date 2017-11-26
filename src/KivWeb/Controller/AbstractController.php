<?php
namespace Esler\KivWeb\Controller;

use Esler\KivWeb\Exception;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest as Request;

abstract class AbstractController
{
    private $request;
    private $response;

    public function dispatch(Request $request): Response {
        if (empty($request->getQueryParams()['action'])) {
            throw new Exception\ServerError('No value for :action found');
        }

        $this->request  = $request;
        $this->response = new Response;

        // resolve action name
        $method = 'action' . ucfirst($request->getQueryParams()['action']);

        // call controller action
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            throw new Exception\NotFound('Method ' . $method . ' not found in ' . static::class);
        }

        return $this->getResponse();
    }

    protected function getRequest(): Request {
        return $this->request;
    }

    protected function getResponse(): Response {
        return $this->response;
    }

    protected function setResponse(Response $response): void {
        $this->response = $response;
    }
}
