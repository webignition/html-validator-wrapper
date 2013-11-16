<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\Configuration\Configuration;
use webignition\HtmlValidator\Wrapper\Wrapper;

class HasConfigurationTest extends \PHPUnit_Framework_TestCase {
    
    public function testHasNotConfigurationByDefault() {
        $wrapper = new Wrapper();
        $this->assertFalse($wrapper->hasConfiguration());
    }
    
    public function testHasConfigurationWhenConfigurationIsSet() {
        $wrapper = new Wrapper();
        $this->assertTrue($wrapper->setConfiguration(new Configuration())->hasConfiguration());
    }    
    
}