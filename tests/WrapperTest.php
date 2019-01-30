<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\Tests\HtmlValidator\Wrapper;

use Mockery\MockInterface;
use webignition\HtmlValidator\Output\Output;
use webignition\HtmlValidator\Output\Parser\Parser as OutputParser;
use webignition\HtmlValidator\Wrapper\CommandExecutor;
use webignition\HtmlValidator\Wrapper\CommandFactory;
use webignition\HtmlValidator\Wrapper\Wrapper;

class WrapperTest extends \PHPUnit\Framework\TestCase
{
    const VALIDATOR_PATH = '/usr/local/validator/cgi-bin/check';

    public function testCreateConfigurationWithoutDocumentUri()
    {
        $wrapper = $this->createWrapper(
            new CommandFactory(self::VALIDATOR_PATH),
            new CommandExecutor(new OutputParser())
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration value "document-uri" not set');
        $this->expectExceptionCode(1);

        $wrapper->configure();
    }

    public function testValidateWithoutDocumentUri()
    {
        $wrapper = $this->createWrapper(
            new CommandFactory(self::VALIDATOR_PATH),
            new CommandExecutor(new OutputParser())
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration value "document-uri" not set');
        $this->expectExceptionCode(2);

        $wrapper->validate();
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(array $configurationValues, string $expectedCommand)
    {
        /* @var MockInterface|Output $output */
        $output = \Mockery::mock(Output::class);

        $commandExecutor = $this->createCommandExecutor($expectedCommand, $output);

        $wrapper = $this->createWrapper(
            new CommandFactory(self::VALIDATOR_PATH),
            $commandExecutor
        );

        $wrapper->configure($configurationValues);
        $validationOutput = $wrapper->validate();

        $this->assertSame($output, $validationOutput);
    }

    public function validateDataProvider(): array
    {
        return [
            'no errors, default configuration' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/',
                    Wrapper::CONFIG_KEY_DOCUMENT_CHARACTER_SET => 'utf-8',
                ],
                'expectedCommand' =>
                    '/usr/local/validator/cgi-bin/check output=json charset=utf-8 uri=http://example.com/',
            ],
            'three errors, default configuration' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/',
                    Wrapper::CONFIG_KEY_DOCUMENT_CHARACTER_SET => 'utf-8',
                ],
                'expectedCommand' =>
                    '/usr/local/validator/cgi-bin/check output=json charset=utf-8 uri=http://example.com/',
            ],
            'no errors, non-default configuration' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_CHARACTER_SET => 'utf-16',
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/'
                ],
                'expectedCommand' =>
                    '/usr/local/validator/cgi-bin/check output=json charset=utf-16 uri=http://example.com/',
            ],
        ];
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
