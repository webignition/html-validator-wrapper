<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\Tests\Mock\HtmlValidator\Wrapper\Wrapper as MockHtmlValidatorWrapper;


/**
 * Test that we can create and use a mock CSS validator wrapper
 * for use in unit tests whereby pre-determined validator output can be
 * set and used.
 */
class MockHtmlValidatorWrapperTest extends \PHPUnit_Framework_TestCase {
    
    public function testMockWrapperIsInstanceOfActualWrapper() {
        $mockWrapper = new MockHtmlValidatorWrapper();
        $this->assertInstanceOf('\webignition\HtmlValidator\Wrapper\Wrapper', $mockWrapper);
    }
    
}