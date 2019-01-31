<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\Tests\HtmlValidator\Wrapper;

use Mockery\MockInterface;
use webignition\HtmlValidator\Wrapper\CommandExecutor;
use webignition\HtmlValidator\Wrapper\CommandFactory;
use webignition\HtmlValidator\Wrapper\Wrapper;
use webignition\HtmlValidatorOutput\Models\Output;

class WrapperTest extends \PHPUnit\Framework\TestCase
{
    const VALIDATOR_PATH = '/usr/local/validator/cgi-bin/check';

    public function testValidate()
    {
        $uri = 'file:/tmp/document.html';
        $documentCharacterSet = 'utf-8';
        $expectedCommand = '/usr/local/validator/cgi-bin/check output=json charset=utf-8 uri=file:/tmp/document.html';

        /* @var MockInterface|Output $output */
        $output = \Mockery::mock(Output::class);

        $commandExecutor = $this->createCommandExecutor($expectedCommand, $output);

        $wrapper = $this->createWrapper(
            new CommandFactory(self::VALIDATOR_PATH),
            $commandExecutor
        );

        $validationOutput = $wrapper->validate($uri, $documentCharacterSet);

        $this->assertSame($output, $validationOutput);
    }

    private function createWrapper(CommandFactory $commandFactory, CommandExecutor $commandExecutor): Wrapper
    {
        return new Wrapper($commandFactory, $commandExecutor);
    }

    /**
     * @return MockInterface|CommandExecutor
     */
    private function createCommandExecutor(string $expectedCommand, Output $output)
    {
        $commandExecutor = \Mockery::mock(CommandExecutor::class);
        $commandExecutor
            ->shouldReceive('execute')
            ->with($expectedCommand)
            ->andReturn($output);

        return $commandExecutor;
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        parent::tearDown();
        \Mockery::close();
    }
}
