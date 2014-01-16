<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\Configuration\Configuration;
use webignition\HtmlValidator\Wrapper\Wrapper;

class CreateConfigurationTest extends \PHPUnit_Framework_TestCase {
    
    public function testPassNonArrayArgumentThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException');
        
        $wrapper = new Wrapper();
        $wrapper->createConfiguration(null);        
    }    
    

    public function testPassEmptyArrayArgumentThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException');
        
        $wrapper = new Wrapper();
        $wrapper->createConfiguration(array());        
    }    
    
    public function testMissingDocumentUriThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException');
        
        $wrapper = new Wrapper();
        $this->assertInstanceOf('webignition\HtmlValidator\Wrapper\Configuration\Configuration', $wrapper->createConfiguration(array(
            'foo' => 'bar'
        )));        
    }    
    
    public function testCreateConfigurationReturnsSelf() {
        $wrapper = new Wrapper();
        $this->assertInstanceOf('webignition\HtmlValidator\Wrapper\Wrapper', $wrapper->createConfiguration(array(
            'documentUri' => 'http://example.com/'
        )));        
    }
    
    public function testSetDocumentCharacterSet() {
        $characterSet = 'foo';
        
        $wrapper = new Wrapper();
        $wrapper->createConfiguration(array(
            'documentUri' => 'http://example.com/',
            'documentCharacterSet' => $characterSet
        ));
        
        $this->assertEquals($characterSet, $wrapper->getConfiguration()->getDocumentCharacterSet());                
    }
    
}