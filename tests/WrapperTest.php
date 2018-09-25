<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use phpmock\mockery\PHPMockery;
use webignition\HtmlValidator\Wrapper\Wrapper;

class WrapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Wrapper
     */
    private $wrapper;

    protected function setUp()
    {
        parent::setUp();
        $this->wrapper = new Wrapper();
    }

    public function testCreateConfigurationWithoutDocumentUri()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration value "document-uri" not set');
        $this->expectExceptionCode(1);

        $this->wrapper->createConfiguration([]);
    }

    public function testValidateWithoutDocumentUri()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration value "document-uri" not set');
        $this->expectExceptionCode(2);

        $this->wrapper->validate();
    }

    /**
     * @dataProvider getExecutableCommandDataProvider
     *
     * @param $configurationValues
     * @param $expectedExecutableCommand
     */
    public function testGetExecutableCommand($configurationValues, $expectedExecutableCommand)
    {
        $this->wrapper->createConfiguration($configurationValues);
        $this->assertEquals($expectedExecutableCommand, $this->wrapper->getExecutableCommand());
    }

    /**
     * @return array
     */
    public function getExecutableCommandDataProvider()
    {
        return [
            'default' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com',
                ],
                'expectedExecutableCommand' => '/usr/local/validator/cgi-bin/check output=json uri=http://example.com',
            ],
            'non-default' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com',
                    Wrapper::CONFIG_KEY_VALIDATOR_PATH => '/foo',
                    Wrapper::CONFIG_KEY_DOCUMENT_CHARACTER_SET => 'utf-8',
                ],
                'expectedExecutableCommand' => '/foo output=json charset=utf-8 uri=http://example.com',
            ],
        ];
    }

    /**
     * @dataProvider validateDataProvider
     *
     * @param array $configurationValues
     * @param string $htmlValidatorRawOutput
     * @param int $expectedErrorCount
     */
    public function testValidate($configurationValues, $htmlValidatorRawOutput, $expectedErrorCount)
    {
        $wrapper = new Wrapper();
        $wrapper->createConfiguration($configurationValues);
        $this->setHtmlValidatorRawOutput($htmlValidatorRawOutput);
        $output = $wrapper->validate();

        $this->assertEquals($expectedErrorCount, $output->getErrorCount());
    }

    /**
     * @return array
     */
    public function validateDataProvider()
    {
        return [
            'no errors' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/'
                ],
                'htmlValidatorRawOutput' => $this->loadHtmlValidatorRawOutputFixture('0-errors'),
                'expectedErrorCount' => 0,
            ],
            'three errors' => [
                'configurationValues' => [
                    Wrapper::CONFIG_KEY_DOCUMENT_URI => 'http://example.com/'
                ],
                'htmlValidatorRawOutput' => $this->loadHtmlValidatorRawOutputFixture('3-errors'),
                'expectedErrorCount' => 3,
            ],
        ];
    }


    /**
     * @param string $name
     *
     * @return string
     */
    private function loadHtmlValidatorRawOutputFixture($name)
    {
        return file_get_contents(__DIR__ . '/fixtures/raw-html-validator-output/' . $name . '.txt');
    }

    /**
     * @param string $rawOutput
     */
    private function setHtmlValidatorRawOutput($rawOutput)
    {
        PHPMockery::mock(
            'webignition\HtmlValidator\Wrapper',
            'shell_exec'
        )->andReturn(
            $rawOutput
        );
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
