<?php

namespace webignition\HtmlValidator\Mock\Wrapper;

use webignition\HtmlValidator\Wrapper\Wrapper as BaseHtmlValidatorWrapper;

class Wrapper extends BaseHtmlValidatorWrapper {    
    
    /**
     *
     * @var array
     */
    private $validatorRawOutput = array();
    
    
    /**
     * 
     * @param string $rawOutput
     * @return \webignition\Tests\Mock\HtmlValidator\Wrapper\Wrapper
     */
    public function setHtmlValidatorRawOutput($rawOutput) {
        $this->validatorRawOutput[] = $rawOutput;
        return $this;
    }
    
    
    /**
     * 
     * @param string $fixturePath
     * @return \webignition\HtmlValidator\Mock\Wrapper\Wrapper
     */
    public function loadFixturesFromPath($fixturePath) {                
        if (file_exists($fixturePath) && is_dir($fixturePath)) {
            $fixtureFilePaths = array();
            
            $directoryIterator = new \DirectoryIterator($fixturePath);
            foreach ($directoryIterator as $directoryItem) {
                if ($directoryItem->isFile()) {
                    $fixtureFilePaths[] = $directoryItem->getPathname();
                }
            }
            
            sort($fixtureFilePaths);            
            foreach ($fixtureFilePaths as $fixtureFilePath) {
                $this->validatorRawOutput[] = file_get_contents($fixtureFilePath);
            }
        }
        
        return $this;
    }
    
    /**
     * 
     * @return array|null
     */
    public function getRawValidatorOutputLines() {
        if (is_null(key($this->validatorRawOutput))) {
            return null;
        }
        
        $content = explode("\n", current($this->validatorRawOutput));
        next($this->validatorRawOutput);
        
        return $content;
    }
    
}