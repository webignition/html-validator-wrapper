<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Mock\Wrapper\Wrapper as MockHtmlValidatorWrapper;


/**
 * Test that we can create and use a mock CSS validator wrapper
 * for use in unit tests whereby pre-determined validator output can be
 * set and used.
 */
class MockHtmlValidatorWrapperTest extends BaseTest {
    
    public function testMockWrapperIsInstanceOfActualWrapper() {
        $mockWrapper = new MockHtmlValidatorWrapper();
        $this->assertInstanceOf('\webignition\HtmlValidator\Wrapper\Wrapper', $mockWrapper);
    }
    
    public function testLoadFixturesFromPathReturnsSelf() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        
        $mockWrapper = new MockHtmlValidatorWrapper();
        $this->assertEquals($mockWrapper, $mockWrapper->loadFixturesFromPath($this->getTestFixturePath()));
    }
    
    public function testGetRawValidatorOutputLinesFromSingleFixtureFromFixturePath() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        
        $mockWrapper = new MockHtmlValidatorWrapper();
        $mockWrapper->loadFixturesFromPath($this->getTestFixturePath());
        
        $this->assertEquals(explode("\n", file_get_contents($this->getTestFixturePath() . '/0-errors.txt')), $mockWrapper->getRawValidatorOutputLines());
    }
    
    public function testGetRawValidatorOutputLinesFromMultipleFixturesFromFixturePath() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        
        $mockWrapper = new MockHtmlValidatorWrapper();
        $mockWrapper->loadFixturesFromPath($this->getTestFixturePath());
        
        $this->assertEquals(explode("\n", file_get_contents($this->getTestFixturePath() . '/0-errors.txt')), $mockWrapper->getRawValidatorOutputLines());
        $this->assertEquals(explode("\n", file_get_contents($this->getTestFixturePath() . '/3-errors.txt')), $mockWrapper->getRawValidatorOutputLines());
    } 
    
    
    public function testGetRawValidatorOutputLinesFromFixturePathWithNoFixturesReturnsNull() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        
        $mockWrapper = new MockHtmlValidatorWrapper();
        $mockWrapper->loadFixturesFromPath($this->getTestFixturePath());
        
        $this->assertNull($mockWrapper->getRawValidatorOutputLines());
    } 
    
    public function testGetRawValidatorOutputLinesFromMultipleFixturesReturnsNullWhenNoFixuresRemain() {
        $this->setTestFixturePath(__CLASS__, $this->getName());
        
        $mockWrapper = new MockHtmlValidatorWrapper();
        $mockWrapper->loadFixturesFromPath($this->getTestFixturePath());
        
        $this->assertNotNull($mockWrapper->getRawValidatorOutputLines());
        $this->assertNotNull($mockWrapper->getRawValidatorOutputLines());
        $this->assertNull($mockWrapper->getRawValidatorOutputLines());
    }     
    
    
}