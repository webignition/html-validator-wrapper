<?php

namespace webignition\HtmlValidator\Wrapper;

use webignition\HtmlValidatorOutput\Models\Output;
use webignition\HtmlValidatorOutput\Parser\Flags;

class Wrapper
{
    private $commandFactory;
    private $commandExecutor;

    public function __construct(CommandFactory $commandFactory, CommandExecutor $commandExecutor)
    {
        $this->commandFactory = $commandFactory;
        $this->commandExecutor = $commandExecutor;
    }

    public function validate(string $uri, string $documentCharacterSet, ?int $flags = Flags::NONE): Output
    {
        return $this->commandExecutor->execute(
            $this->commandFactory->create($uri, $documentCharacterSet),
            $flags
        );
    }
}
