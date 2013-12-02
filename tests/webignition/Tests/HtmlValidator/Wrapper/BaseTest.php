<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\Wrapper as HtmlValidatorWrapper;
use webignition\HtmlValidator\Wrapper\Configuration\Configuration;
use webignition\HtmlValidator\Mock\Wrapper\Wrapper as MockHtmlValidatorWrapper;

abstract class BaseTest extends \PHPUnit_Framework_TestCase {
    
    
    /**
     * 
     * @return \webignition\Tests\Mock\HtmlValidator\Wrapper\Wrapper
     */
    public function getMockHtmlValidatorWrapper() {
        return new MockHtmlValidatorWrapper();
    }
    
    
    /**
     * 
     * @return \webignition\Tests\Mock\HtmlValidator\Wrapper\Wrapper
     */
    public function getHtmlValidatorWrapper() {
        return new HtmlValidatorWrapper();
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
        $this->fixturePath = __DIR__ . self::FIXTURES_BASE_PATH . '/' . str_replace('\\', '/', $testClass) . '/' . $testMethod;       
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
    
    
    /**
     * 
     * @return string
     */
    protected function getCommonFixturesPath() {
        return __DIR__ . self::FIXTURES_BASE_PATH . '/Common/';
    }
    
    
    protected function getAndStoreValidatorOutput($documentUri) {
        $configuration = new Configuration();
        $configuration->setDocumentUri($documentUri);

        $wrapper = $this->getHtmlValidatorWrapper(); 
        $wrapper->setConfiguration($configuration);
        
        file_put_contents($this->getCommonFixturesPath() . md5($documentUri) . '.txt', implode("\n", $wrapper->getRawValidatorOutputLines()));
    }
}