<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use phpmock\mockery\PHPMockery;
use webignition\HtmlValidator\Output\Output;
use webignition\HtmlValidator\Output\Parser\Parser as OutputParser;
use webignition\HtmlValidator\Wrapper\CommandExecutor;

class CommandExecutorTest extends \PHPUnit\Framework\TestCase
{
    public function testExecute()
    {
        $command = '/command';
        $commandExecutor = new CommandExecutor(new OutputParser());

        $this->createShellExecCallExpectation(
            file_get_contents(__DIR__ . '/fixtures/raw-html-validator-output/0-errors.txt'),
            $command
        );

        $output = $commandExecutor->execute($command);

        $this->assertInstanceOf(Output::class, $output);
    }

    private function createShellExecCallExpectation(string $rawOutput, string $expectedExecutableCommand)
    {
        PHPMockery::mock('webignition\HtmlValidator\Wrapper', 'shell_exec')
            ->with($expectedExecutableCommand)
            ->andReturn($rawOutput);
    }
}
