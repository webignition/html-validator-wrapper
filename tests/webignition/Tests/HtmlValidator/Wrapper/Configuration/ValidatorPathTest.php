<?php

namespace webignition\Tests\HtmlValidator\Wrapper\Configuration;

use \webignition\HtmlValidator\Wrapper\Configuration\Configuration;

class ValidatorPathTest extends AbstractPropertyTest {
    
    protected function getPropertyName() {
        return 'validatorPath';
    }    
    
    public function testSetNoValidatorPathGetsDefaultPath() {
        $configuration = new Configuration();
        $this->assertEquals(Configuration::DEFAULT_VALIDATOR_PATH, $configuration->getValidatorPath());
    }
    
}