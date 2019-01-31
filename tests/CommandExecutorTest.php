<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use phpmock\mockery\PHPMockery;
use webignition\HtmlValidatorOutput\Parser\Parser as OutputParser;
use webignition\HtmlValidator\Wrapper\CommandExecutor;
use webignition\HtmlValidatorOutput\Models\Output;
use webignition\ValidatorMessage\MessageList;

class CommandExecutorTest extends \PHPUnit\Framework\TestCase
{
    public function testExecute()
    {
        $validatorRawOutput = file_get_contents(__DIR__ . '/Fixtures/html-validator-output-no-errors.txt');

        $expectedOutput = new Output(new MessageList());


        $outputParser = new OutputParser();

        $command = '/command';
        $this->createShellExecCallExpectation($validatorRawOutput, $command);

        $commandExecutor = new CommandExecutor($outputParser);
        $executorOutput = $commandExecutor->execute($command);

        $this->assertEquals($expectedOutput->getErrorCount(), $executorOutput->getErrorCount());
        $this->assertEquals($expectedOutput->getMessages(), $executorOutput->getMessages());
    }

    private function createShellExecCallExpectation(string $rawOutput, string $expectedExecutableCommand)
    {
        PHPMockery::mock('webignition\HtmlValidator\Wrapper', 'shell_exec')
            ->with($expectedExecutableCommand)
            ->andReturn($rawOutput);
    }
}
