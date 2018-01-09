<?php
namespace Esler\KivWeb;

use GuzzleHttp\Psr7\ServerRequest;

class Request extends ServerRequest
{
    /**
     * Return a Request populated with superglobals:
     * $_GET
     * $_POST
     * $_COOKIE
     * $_FILES
     * $_SERVER
     *
     * @return Request
     */
    public static function fromGlobals(): Request
    {
        $parent = parent::fromGlobals();

        $request = new Request(
            $parent->getMethod(),
            $parent->getUri(),
            $parent->getHeaders(),
            $parent->getBody(),
            $parent->getProtocolVersion(),
            $parent->getServerParams()
        );

        return $request
            ->withCookieParams($parent->getCookieParams())
            ->withQueryParams($parent->getQueryParams())
            ->withParsedBody($parent->getParsedBody())
            ->withUploadedFiles($parent->getUploadedFiles());
    }

    /**
     * Gets a parameter from query or body of the request
     *
     * @param string $param   name of parameter
     * @param mixed  $default optional default
     *
     * @return mixed given value
     */
    public function getParam(string $param, $default = null)
    {
        return $this->getQueryParams()[$param] ?? $this->getParsedBody()[$param] ?? $default;
    }

    /**
     * Returns clone with set give parameter
     *
     * @param string $param name of parameter
     * @param mixed  $value a value
     *
     * @return Request
     */
    public function withParam(string $param, $value): Request
    {
        return $this->withQueryParams([$param => $value] + $this->getQueryParams());
    }
}
