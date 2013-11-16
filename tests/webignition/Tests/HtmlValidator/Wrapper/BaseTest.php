<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\Tests\Mock\HtmlValidator\Wrapper\Wrapper as MockHtmlValidatorWrapper;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {
    
    
    /**
     * 
     * @return \webignition\Tests\Mock\HtmlValidator\Wrapper\Wrapper
     */
    public function getNewHtmlValidatorWrapper() {
        return new MockHtmlValidatorWrapper();
    }
    
    
    const FIXTURES_BASE_PATH = '/../../../../fixtures';
    
    /**
     *
     * @var string
     */
    private $fixturePath = null;    

    /**
     * 
     * @param string $testClass
     * @param string $testMethod
     */
    protected function setTestFixturePath($testClass, $testMethod) {
        $this->fixturePath = __DIR__ . self::FIXTURES_BASE_PATH . '/' . $testClass . '/' . $testMethod;       
    }    
    
    
    /**
     * 
     * @return string
     */
    protected function getTestFixturePath() {
        return $this->fixturePath;     
    }
    
    
    /**
     * 
     * @param string $fixtureName
     * @return string
     */
    protected function getFixture($fixtureName) {        
        if (file_exists($this->getTestFixturePath() . '/' . $fixtureName)) {
            return file_get_contents($this->getTestFixturePath() . '/' . $fixtureName);
        }
        
        return file_get_contents(__DIR__ . self::FIXTURES_BASE_PATH . '/Common/' . $fixtureName);        
    }
}