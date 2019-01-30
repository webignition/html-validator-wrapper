<?php

namespace webignition\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Output\Output;
use webignition\HtmlValidator\Output\Parser\Parser as OutputParser;

class CommandExecutor
{
    private $outputParser;

    public function __construct(OutputParser $outputParser)
    {
        $this->outputParser = $outputParser;
    }

    public function execute(string $command): Output
    {
        $validatorOutput = shell_exec($command);

        return $this->outputParser->parse($validatorOutput);
    }
}
