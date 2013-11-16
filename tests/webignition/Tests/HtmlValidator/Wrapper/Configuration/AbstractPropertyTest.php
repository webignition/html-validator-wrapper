<?php

namespace webignition\Tests\HtmlValidator\Wrapper\Configuration;

use \webignition\HtmlValidator\Wrapper\Configuration\Configuration;

abstract class AbstractPropertyTest extends \PHPUnit_Framework_TestCase {
    
    abstract protected function getPropertyName();
    
    public function testSetReturnsSelf() {
        $configuration = new Configuration();
        $methodName = $this->getSetMethodName();
        $this->assertEquals($configuration, $configuration->$methodName(''));        
    }
    
    public function testSetGetsValueSet() {
        $configuration = new Configuration();
        $setMethodName = $this->getSetMethodName();
        $getMethodName = $this->getGetMethodName();
        
        $this->assertEquals('foo', $configuration->$setMethodName('foo')->$getMethodName());        
    }
    
    
    /**
     * 
     * @return string
     */
    private function getSetMethodName() {
        return 'set' . $this->getPropertyName();
    }    
    
    
    /**
     * 
     * @return string
     */
    private function getGetMethodName() {
        return 'get' . $this->getPropertyName();
    }
    
}