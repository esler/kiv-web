<?php
namespace Esler\KivWeb;

use Esler\KivWeb\Utils\Immutable;

class Request
{
    use Immutable;

    private $method;
    private $params = [];
    private $path;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
        $this->params = $_GET;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParam(string $param, $default = null)
    {
        return $this->params[$param] ?? $default;
    }


    public function withMethod(string $method): Request
    {
        return $this->withProperty('method', $method);
    }

    public function withParam(string $param, $value): Request
    {
        $new = clone $this;
        $new->params[$param] = $value;
        return $new;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
