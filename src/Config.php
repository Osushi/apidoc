<?php

namespace Osushi\Apidoc;

class Config
{
    const DOCUMENT_TEMPLATE_PATH = 'document_template_path';
    const DOCUMENT_TOC_TEMPLATE_PATH = 'document_toc_template_path';
    const DOCUMENT_TOC_TITLE = 'document_toc_title';
    const TOC_TEMPLATE_PATH = 'toc_template_path';
    const TOC_TITLE = 'toc_title';
    const OUTPUT_PATH = 'output_path';
    const TOC = 'toc';

    private $config = [
        self::DOCUMENT_TEMPLATE_PATH => '../templates/document.md',
        self::DOCUMENT_TOC_TEMPLATE_PATH => '../templates/document.toc.md',
        self::DOCUMENT_TOC_TITLE => '# Table of Contents',
        self::TOC_TEMPLATE_PATH => '../templates/toc.md',
        self::TOC_TITLE => '# Table of Contents',
        self::OUTPUT_PATH => 'docs',
        self::TOC => true,
    ];

    public function __construct(
        array $config = []
    ) {
        // convert realpath
        $config[self::DOCUMENT_TEMPLATE_PATH] = realpath(__DIR__.'/'.$this->config[self::DOCUMENT_TEMPLATE_PATH]);
        $config[self::DOCUMENT_TOC_TEMPLATE_PATH] = realpath(__DIR__.'/'.$this->config[self::DOCUMENT_TOC_TEMPLATE_PATH]);
        $config[self::TOC_TEMPLATE_PATH] = realpath(__DIR__.'/'.$this->config[self::TOC_TEMPLATE_PATH]);

        foreach ($config as $key => $value) {
            $this->setConfig($key, $value);
        }
    }

    public function setConfig(
        string $key,
        $value
    ) {
        if (array_key_exists($key, $this->config)) {
            $this->config[$key] = $value;
        }
    }

    public function getConfig(
        string $key
    ) {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }
        return false;
    }
}
