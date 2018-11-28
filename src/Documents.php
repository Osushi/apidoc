<?php

namespace Osushi\Apidoc;

use Osushi\Apidoc\Config;
use Osushi\Apidoc\Parameter;
use Osushi\Apidoc\Request;
use Osushi\Apidoc\Response;

class Documents
{
    const KEY_DELIMITER = ':';

    private $table = [];

    private $config;

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    public function record(
        string $key,
        Parameter $parameter = null,
        string $comment = ''
    ) {
        list($filename, $path, $method) = $this->getKeys($key);

        $this->table[$filename][$path][$method] = [
            'comment' => $comment,
            'parameters' => $parameter,
            'examples' => [],
        ];
    }

    public function example(
        string $key,
        Request $request,
        Response $response,
        string $comment = ''
    ) {
        list($filename, $path, $method) = $this->getKeys($key);

        $this->table[$filename][$path][$method]['examples'][] = [
            'comment' => $comment,
            'request' => $request,
            'response' => $response,
        ];
    }

    public function render()
    {
        if (!in_array('APIDOC', $_SERVER['argv'])) {
            return false;
        }
        if (!file_exists($this->config->getConfig($this->config::OUTPUT_PATH))) {
            throw new \Exception('Unable to find output directory [' . $this->config->getConfig($this->config::OUTPUT_PATH) . ']');
        }
        $output = $this->config->getConfig($this->config::OUTPUT_PATH);

        if (count($this->table)) {
            $template = $this->load();
            $toc = [];
            foreach ($this->table as $filepath => $contents) {
                $toc[$filepath] = [];
                $documentBuffers = [];
                $documentTocBuffers = [];

                foreach ($contents as $path => $documents) {
                    foreach ($documents as $method => $document) {
                        // document
                        $documentBuffers[] = $template->render('document.md', [
                            'path' => $path,
                            'method' => $method,
                            'document' => $document,
                        ]);
                        // toc document
                        $documentTocBuffers[] = $template->render('document.toc.md', [
                            'path' => $path,
                            'method' => $method,
                        ]);
                        // toc
                        $toc[$filepath][] = [
                            'path' => $path,
                            'method' => $method,
                        ];
                    }
                }

                $string = '';
                if (count($documentTocBuffers)) {
                    array_unshift($documentTocBuffers, $this->config->getConfig($this->config::DOCUMENT_TOC_TITLE));
                    $string .= implode("\n", $documentTocBuffers);
                }
                if (count($documentBuffers)) {
                    $string .= implode("\n", $documentBuffers);
                }

                if (!empty($string)) {
                    file_put_contents($output.'/'.$filepath.'.md', $string);
                }
            }

            if ($this->config->getConfig($this->config::TOC)) {
                $tocBuffers = [];
                foreach ($toc as $filepath => $contents) {
                    $tocBuffers[] = $template->render('toc.md', [
                        'filepath' => $filepath,
                        'contents' => $contents,
                    ]);
                }

                $string = '';
                if (count($tocBuffers)) {
                    array_unshift($tocBuffers, $this->config->getConfig($this->config::TOC_TITLE));
                    $string .= implode("\n", $tocBuffers);
                    file_put_contents($output.'/toc.md', $string);
                }
            }
        }
    }

    public function getTable()
    {
        return $this->table;
    }

    protected function load()
    {
        $loader = new \Twig_Loader_Array([
            'document.md' => file_get_contents(
                $this->config->getConfig($this->config::DOCUMENT_TEMPLATE_PATH)
            ),
            'document.toc.md' => file_get_contents(
                $this->config->getConfig($this->config::DOCUMENT_TOC_TEMPLATE_PATH)
            ),
            'toc.md' => file_get_contents(
                $this->config->getConfig($this->config::TOC_TEMPLATE_PATH)
            ),
        ]);
        return new \Twig_Environment($loader);
    }

    protected function getKeys(
        string $key
    ) {
        $keys = explode(self::KEY_DELIMITER, $key);
        if (count($keys) === 3) {
            return $keys;
        }

        throw new \Exception('Key must set {file_path}:{path}:{method}[' . $key . ']');
    }
}
