<?php

namespace webignition\Tests\HtmlValidator\Wrapper\Configuration;

use webignition\Tests\HtmlValidator\Wrapper\BaseTest;
use \webignition\HtmlValidator\Wrapper\Configuration\Configuration;

class DocumentCharacterSetTest extends BaseTest {
    
    protected function getPropertyName() {
        return 'documentCharacterSet';
    }    
    
    public function testDefaultValueIsNull() {        
        $configuration = new Configuration();    
        $this->assertNull($configuration->getDocumentCharacterSet());
    }    
    
    public function testDefaultHasDocumentCharacterSetIsFalse() {        
        $configuration = new Configuration();    
        $this->assertFalse($configuration->hasDocumentCharacterSet());
    }     
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $this->assertEquals($configuration, $configuration->setDocumentCharacterSet('utf8'));
    }    
    
    public function testgetReturnsSetValue() {
        $charset = 'utf8';
        $configuration = new Configuration();        
        $this->assertEquals($charset, $configuration->setDocumentCharacterSet($charset)->getDocumentCharacterSet());
    }
    
}