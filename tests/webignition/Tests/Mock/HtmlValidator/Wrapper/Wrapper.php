<?php

namespace webignition\Tests\Mock\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\Wrapper as BaseHtmlValidatorWrapper;

class Wrapper extends BaseHtmlValidatorWrapper {    
    
    /**
     *
     * @var string
     */
    private $cssValidatorRawOutput = null;
    
    
    /**
     * 
     * @param string $rawOutput
     * @return \webignition\Tests\Mock\HtmlValidator\Wrapper\Wrapper
     */
    public function setHtmlValidatorRawOutput($rawOutput) {
        $this->cssValidatorRawOutput = $rawOutput;
        return $this;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getRawValidatorOutputLines() {
        return explode("\n", $this->cssValidatorRawOutput);
    }    
    
}