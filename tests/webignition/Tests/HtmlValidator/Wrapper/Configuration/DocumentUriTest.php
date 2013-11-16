<?php

namespace webignition\Tests\HtmlValidator\Wrapper\Configuration;

use \webignition\HtmlValidator\Wrapper\Configuration\Configuration;

class DocumentUriTest extends AbstractPropertyTest {
    
    protected function getPropertyName() {
        return 'documentUri';
    }    
    
    public function testGetWithoutSettingThrowsInvalidArgumentException() {
        $this->setExpectedException('InvalidArgumentException');      
        
        $configuration = new Configuration();        
        $configuration->getDocumentUri();
    }
    
}