<?php

namespace Osushi\Apidoc;

class Response
{
    private $code;

    private $headers;

    private $body;

    public function __construct(
        array $responses = []
    ) {
        foreach ($responses as $key => $value) {
            switch ($key) {
            case 'code':
                $this->setCode($value);
                break;
            case 'headers':
                $this->setHeaders($value);
                break;
            case 'body':
                $this->setBody($value);
                break;
            }
        }
    }

    public function setCode(
        int $code
    ) {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
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

    public function setBody(
        string $body
    ) {
        $this->body = json_decode($body, true);
    }

    public function getBody()
    {
        return $this->body;
    }
}
