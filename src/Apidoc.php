<?php

namespace Osushi\Apidoc;

use Osushi\Apidoc\Config;
use Osushi\Apidoc\Documents;

class Apidoc
{
    private static $instance;

    private $documents;

    public static function init(
        array $config = []
    ) {
        return self::$instance = new self($config);
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            throw new \Exception('Please call Apidoc::init');
        }

        return self::$instance;
    }

    public function __construct(
        array $config = []
    ) {
        $this->documents = new Documents(
            new Config($config)
        );
    }

    public function __call(
        string $method,
        $parameters
    ) {
        method_exists($this, $method) ? $this->$method(...$parameters) : $this->documents->$method(...$parameters);
    }
}
