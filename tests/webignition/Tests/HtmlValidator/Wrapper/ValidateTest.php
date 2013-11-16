<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\Configuration\Configuration;

class ValidateTest extends BaseTest {
    
    public function setUp() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
    }     
    
    public function testValidateWithoutSettingConfigurationThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException');
        
        $wrapper = $this->getNewHtmlValidatorWrapper();        
        $wrapper->validate();
    }
    
    public function testTest() {        
        $configuration = new Configuration();
        $configuration->setDocumentUri('file:/foo/example.html');

        $wrapper = $this->getNewHtmlValidatorWrapper(); 
        $wrapper->setConfiguration($configuration);
        $wrapper->setHtmlValidatorRawOutput($this->getFixture('output/error-free.txt'));
        
        $wrapper->validate();
    }
    
}