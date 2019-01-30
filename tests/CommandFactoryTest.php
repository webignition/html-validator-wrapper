<?php
/** @noinspection PhpDocSignatureInspection */

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\CommandFactory;

class CommandFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        string $validatorPath,
        string $uri,
        string $documentCharacterSet,
        string $expectedCommand
    ) {
        $commandFactory = new CommandFactory($validatorPath);

        $this->assertEquals(
            $expectedCommand,
            $commandFactory->create($uri, $documentCharacterSet)
        );
    }

    public function createDataProvider(): array
    {
        return [
            'default' => [
                'validatorPath' => '/validator',
                'url' => 'file:/tmp/document.html',
                'documentCharacterSet' => 'utf-8',
                'expectedCommand' => '/validator output=json charset=utf-8 uri=file:/tmp/document.html',
            ],
        ];
    }
}
