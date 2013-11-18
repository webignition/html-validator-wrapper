<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\Configuration\Configuration;

class ValidateTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }     
    
    public function testValidateWithoutSettingConfigurationThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException');
        
        $wrapper = $this->getMockHtmlValidatorWrapper();        
        $wrapper->validate();
    }
    
    public function testValidateReturnsHtmlValdiatorOutputObject() {        
        $configuration = new Configuration();
        $configuration->setDocumentUri('file:/foo/example.html');

        $wrapper = $this->getMockHtmlValidatorWrapper(); 
        $wrapper->setConfiguration($configuration);
        $wrapper->setHtmlValidatorRawOutput($this->getFixture('0-errors.txt'));
        
        $this->assertInstanceOf('webignition\HtmlValidator\Output\Output', $wrapper->validate());
    }
    
}