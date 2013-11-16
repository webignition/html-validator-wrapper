<?php

namespace webignition\Tests\HtmlValidator\Wrapper\Configuration;

use \webignition\HtmlValidator\Wrapper\Configuration\Configuration;

class GetExecutableCommandTest extends \PHPUnit_Framework_TestCase {   
    
    public function testGetWithoutSettingContentThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException');      
        
        $configuration = new Configuration();
        $configuration->getExecutableCommand();        
    }
    
    public function testExecutableCommandFeaturesEncodedContent() {
        $configuration = new Configuration();
        $configuration->setDocumentUri('file:/foo/example.html');
        
        $this->assertEquals('/usr/local/validator/cgi-bin/check output=json uri=file:/foo/example.html', $configuration->getExecutableCommand());
    }
}