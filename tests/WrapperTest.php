<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use Mockery\MockInterface;
use phpmock\mockery\PHPMockery;
use webignition\HtmlValidator\Output\Parser\Configuration as ParserConfiguration;
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
     *
     * @param array $configurationValues
     * @param string $htmlValidatorRawOutput
     * @param string $expectedExecutableCommand
     * @param int $expectedErrorCount
     */
    public function testValidate(
        array $configurationValues,
        string $htmlValidatorRawOutput,
        string $expectedExecutableCommand,
        int $expectedErrorCount
    ) {
        $wrapper = $this->createWrapper(
            new CommandFactory(self::VALIDATOR_PATH),
            new CommandExecutor(new OutputParser())
        );

        $wrapper->configure($configurationValues);
        $this->createWrapperShellExecCallExpectation($htmlValidatorRawOutput, $expectedExecutableCommand);
        $output = $wrapper->validate();

        $this->assertEquals($expectedErrorCount, $output->getErrorCount());
    }

    public function validateDataProvider(): array
    {
        return [
            'no errors, default configuration' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/',
                    Wrapper::CONFIG_KEY_DOCUMENT_CHARACTER_SET => 'utf-8',
                ],
                'htmlValidatorRawOutput' => $this->loadHtmlValidatorRawOutputFixture('0-errors'),
                'expectedExecutableCommand' =>
                    '/usr/local/validator/cgi-bin/check output=json charset=utf-8 uri=http://example.com/',
                'expectedErrorCount' => 0,
            ],
            'three errors, default configuration' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/',
                    Wrapper::CONFIG_KEY_DOCUMENT_CHARACTER_SET => 'utf-8',
                ],
                'htmlValidatorRawOutput' => $this->loadHtmlValidatorRawOutputFixture('3-errors'),
                'expectedExecutableCommand' =>
                    '/usr/local/validator/cgi-bin/check output=json charset=utf-8 uri=http://example.com/',
                'expectedErrorCount' => 3,
            ],
            'no errors, non-default configuration' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_CHARACTER_SET => 'utf-16',
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/'
                ],
                'htmlValidatorRawOutput' => $this->loadHtmlValidatorRawOutputFixture('0-errors'),
                'expectedExecutableCommand' =>
                    '/usr/local/validator/cgi-bin/check output=json charset=utf-16 uri=http://example.com/',
                'expectedErrorCount' => 0,
            ],
        ];
    }

    /**
     * @dataProvider configureOutputParserDataProvider
     *
     * @param array $parserConfigurationValues
     * @param string $htmlValidatorRawOutput
     * @param array $expectedOutputParserConfigurationValues
     */
    public function testConfigureOutputParser(
        array $parserConfigurationValues,
        string $htmlValidatorRawOutput,
        array $expectedOutputParserConfigurationValues
    ) {
        $expectedExecutableCommand =
            '/usr/local/validator/cgi-bin/check output=json charset=utf-8 uri=http://example.com/';

        /* @var MockInterface|OutputParser $outputParser */
        $outputParser = \Mockery::mock(OutputParser::class);
        $outputParser
            ->shouldReceive('configure')
            ->withArgs(function ($outputParserConfigurationValues) use ($expectedOutputParserConfigurationValues) {
                $this->assertEquals($expectedOutputParserConfigurationValues, $outputParserConfigurationValues);

                return true;
            });

        $outputParser
            ->shouldReceive('parse')
            ->with($htmlValidatorRawOutput);

        $wrapper = $this->createWrapper(
            new CommandFactory(self::VALIDATOR_PATH),
            new CommandExecutor($outputParser)
        );

        $wrapper->setOutputParser($outputParser);
        $wrapper->configure([
            Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/',
            Wrapper::CONFIG_KEY_DOCUMENT_CHARACTER_SET => 'utf-8',
            Wrapper::CONFIG_KEY_PARSER_CONFIGURATION_VALUES => $parserConfigurationValues,
        ]);
        $this->createWrapperShellExecCallExpectation($htmlValidatorRawOutput, $expectedExecutableCommand);

        $wrapper->validate();
    }

    public function configureOutputParserDataProvider(): array
    {
        return [
            'no configuration' => [
                'parserConfigurationValues' => [],
                'htmlValidatorRawOutput' => $this->loadHtmlValidatorRawOutputFixture('0-errors'),
                'expectedOutputParserConfigurationValues' => [],
            ],
            'has configuration' => [
                'parserConfigurationValues' => [
                    ParserConfiguration::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES => true,
                    ParserConfiguration::KEY_CSS_VALIDATION_ISSUES => true,
                ],
                'htmlValidatorRawOutput' => $this->loadHtmlValidatorRawOutputFixture('0-errors'),
                'expectedOutputParserConfigurationValues' => [
                    ParserConfiguration::KEY_IGNORE_AMPERSAND_ENCODING_ISSUES => true,
                    ParserConfiguration::KEY_CSS_VALIDATION_ISSUES => true,
                ],
            ],
        ];
    }

    private function loadHtmlValidatorRawOutputFixture(string $name): string
    {
        return file_get_contents(__DIR__ . '/fixtures/raw-html-validator-output/' . $name . '.txt');
    }

    private function createWrapperShellExecCallExpectation(string $rawOutput, string $expectedExecutableCommand)
    {
        PHPMockery::mock('webignition\HtmlValidator\Wrapper', 'shell_exec')
            ->with($expectedExecutableCommand)
            ->andReturn($rawOutput);
    }

    private function createWrapper(CommandFactory $commandFactory, CommandExecutor $commandExecutor): Wrapper
    {
        return new Wrapper($commandFactory, $commandExecutor);
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
