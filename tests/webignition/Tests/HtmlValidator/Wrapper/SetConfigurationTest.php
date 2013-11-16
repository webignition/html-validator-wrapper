<?php

namespace webignition\Tests\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\Configuration\Configuration;
use webignition\HtmlValidator\Wrapper\Wrapper;

class SetConfigurationTest extends \PHPUnit_Framework_TestCase {
    
    public function testSetConfigurationReturnsSelf() {
        $wrapper = new Wrapper();
        $this->assertEquals($wrapper, $wrapper->setConfiguration(new Configuration()));        
    }
    
}