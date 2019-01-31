<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use Mockery\MockInterface;
use phpmock\mockery\PHPMockery;
use webignition\HtmlValidatorOutput\Parser\Parser as OutputParser;
use webignition\HtmlValidator\Wrapper\CommandExecutor;
use webignition\HtmlValidatorOutput\Models\Output;

class CommandExecutorTest extends \PHPUnit\Framework\TestCase
{
    public function testExecute()
    {
        $validatorRawOutput = 'valid validator raw output';
        $output = \Mockery::mock(Output::class);

        /* @var MockInterface|OutputParser $outputParser */
        $outputParser = \Mockery::mock(OutputParser::class);
        $outputParser
            ->shouldReceive('parse')
            ->with($validatorRawOutput)
            ->andReturn($output);

        $command = '/command';
        $this->createShellExecCallExpectation($validatorRawOutput, $command);

        $commandExecutor = new CommandExecutor($outputParser);
        $executorOutput = $commandExecutor->execute($command);

        $this->assertSame($output, $executorOutput);
    }

    private function createShellExecCallExpectation(string $rawOutput, string $expectedExecutableCommand)
    {
        PHPMockery::mock('webignition\HtmlValidator\Wrapper', 'shell_exec')
            ->with($expectedExecutableCommand)
            ->andReturn($rawOutput);
    }
}
