<?php

namespace Osushi\Apidoc;

class Request
{
    private $method;

    private $path;

    private $parameters;

    private $headers;

    public function __construct(
        array $requests = []
    ) {
        foreach ($requests as $key => $value) {
            switch ($key) {
            case 'method':
                $this->setMethod($value);
                break;
            case 'path':
                $this->setPath($value);
                break;
            case 'parameters':
                $this->setParameters($value);
                break;
            case 'headers':
                $this->setHeaders($value);
                break;
            }
        }
    }

    public function setMethod(
        string $method
    ) {
        $this->method = strtoupper($method);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setPath(
        string $path
    ) {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setParameters(
        array $parameters
    ) {
        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setHeaders(
        array $headers
    ) {
        $this->headers = $headers;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}
