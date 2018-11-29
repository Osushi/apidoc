<?php

namespace Osushi\Apidoc;

class Parameter
{
    const ISA = 'isa';

    const DEFAULT = 'default';

    const REQUIRED = 'required';

    const COMMENT = 'comment';

    const FORMAT = 'format';
    
    const EXCEPT = 'except';

    const ONLY = 'only';

    const ALLOW_OPTIONS = [
        self::ISA,
        self::DEFAULT,
        self::REQUIRED,
        self::COMMENT,
        self::FORMAT,
        self::EXCEPT,
        self::ONLY,
    ];

    private $parameter = [];

    private $note = [];

    public function add(
        string $value,
        array $options
    ) {
        $parameter = [
            'value' => $value,
            'options' => [],
        ];

        foreach ($options as $name => $option) {
            if (in_array($name, self::ALLOW_OPTIONS)) {
                if ($this->validate($name, $option)) {
                    $parameter['options'][$name] = $option;
                }
            }
        }

        if (!empty($parameter['options'][self::REQUIRED])) {
            $this->parameter['required'][] = $parameter;
        } else {
            $this->parameter['option'][] = $parameter;
        }
    }

    public function note(
        string $note
    ) {
        $this->note[] = $note;
    }

    public function getParameter()
    {
        return $this->parameter;
    }

    public function getNote()
    {
        return $this->note;
    }

    protected function validate(
        string $name,
        $option
    ) {
        switch ($name) {
            case self::ISA:
                if (!is_string($option)) {
                    throw new \Exception('Please set string [' . $name . ']');
                }
                break;
            case self::DEFAULT:
                if (!is_string($option)) {
                    throw new \Exception('Please set string [' . $name . ']');
                }
                break;
            case self::REQUIRED:
                if (!is_bool($option)) {
                    throw new \Exception('Please set boolean [' . $name . ']');
                }
                break;
            case self::COMMENT:
                if (!is_string($option)) {
                    throw new \Exception('Please set string [' . $name . ']');
                }
                break;
            case self::FORMAT:
                if (!is_string($option)) {
                    throw new \Exception('Please set string [' . $name . ']');
                }
                break;
            case self::EXCEPT:
                if (!is_array($option)) {
                    throw new \Exception('Please set array [' . $name . ']');
                }
                break;
            case self::ONLY:
                if (!is_array($option)) {
                    throw new \Exception('Please set array [' . $name . ']');
                }
                break;
            default:
                return false;
        }

        return true;
    }
}
