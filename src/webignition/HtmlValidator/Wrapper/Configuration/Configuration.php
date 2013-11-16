<?php

namespace webignition\HtmlValidator\Wrapper\Configuration;

class Configuration {
    
    const DEFAULT_VALIDATOR_PATH = '/usr/local/validator/cgi-bin/check';
    
    /**
     * Path to command-line validator
     * 
     * @var string
     */
    private $validatorPath = null;
    
    
    /**
     *
     * @var string
     */
    private $documentUri = null;
    
    
    
    /**
     * 
     * @param string $validatorPath
     * @return \webignition\HtmlValidator\Wrapper\Configuration\Configuration
     */
    public function setValidatorPath($validatorPath) {
        $this->validatorPath = $validatorPath;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getValidatorPath() {
        return (is_null($this->validatorPath)) ? self::DEFAULT_VALIDATOR_PATH : $this->validatorPath;
    }
    
    
    /**
     * 
     * @param string $documentUri
     * @return \webignition\HtmlValidator\Wrapper\Configuration\Configuration
     */
    public function setDocumentUri($documentUri) {
        $this->documentUri = $documentUri;
        return $this;
    }
    
    
    
    /**
     * 
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getDocumentUri() {
        if (is_null($this->documentUri)) {
            throw new \InvalidArgumentException('Document uri has not been set', 1);
        }
        
        return $this->documentUri;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getExecutableCommand() {
        return $this->getValidatorPath() . ' output=json uri=' . $this->getDocumentUri();        
    }
    
    
    
}