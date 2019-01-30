<?php

namespace webignition\HtmlValidator\Wrapper;

class CommandFactory
{
    private $validatorPath;

    public function __construct(string $validatorPath)
    {
        $this->validatorPath = $validatorPath;
    }

    public function create(string $uri, string $documentCharacterSet)
    {
        return sprintf(
            '%s output=json charset=%s uri=%s',
            $this->validatorPath,
            $documentCharacterSet,
            $uri
        );
    }
}
