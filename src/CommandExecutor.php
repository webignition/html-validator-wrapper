<?php

namespace webignition\HtmlValidator\Wrapper;

use webignition\HtmlValidatorOutput\Parser\Flags;
use webignition\HtmlValidatorOutput\Parser\Parser as OutputParser;
use webignition\HtmlValidatorOutput\Models\Output;

class CommandExecutor
{
    private $outputParser;

    public function __construct(OutputParser $outputParser)
    {
        $this->outputParser = $outputParser;
    }

    public function execute(string $command, ?int $flags = Flags::NONE): Output
    {
        $validatorOutput = shell_exec($command);

        return $this->outputParser->parse($validatorOutput, $flags);
    }
}
