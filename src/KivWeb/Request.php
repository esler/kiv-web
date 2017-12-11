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

    public function getParam(string $param, $default = null)
    {
        return $this->getQueryParams()[$param] ?? $default;
    }

    public function withParam(string $param, $value): Request
    {
        return $this->withQueryParams([$param => $value] + $this->getQueryParams());
    }
}
