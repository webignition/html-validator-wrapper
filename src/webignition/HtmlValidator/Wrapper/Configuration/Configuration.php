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
     * Character set of document being validated.
     * Only relevant if documentUri is of type file:/
     * 
     * @see http://en.wikipedia.org/wiki/Character_encodings_in_HTML     * 
     * @var string
     */
    private $documentCharacterSet = null;
    
    
    /**
     * 
     * @param string $characterSet
     */
    public function setDocumentCharacterSet($characterSet) {
        $this->documentCharacterSet = $characterSet;
        return $this;
    }
    
    
    /**
     * 
     * @return string
     */
    public function getDocumentCharacterSet() {
        return $this->documentCharacterSet;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasDocumentCharacterSet() {
        return is_string($this->documentCharacterSet);
    }
    
    
    
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
        return $this->getValidatorPath() . ' ' . $this->getCommandOptionsString() . ' uri=' . $this->getDocumentUri();        
    }
    
    
    /**
     * 
     * @return string
     */
    private function getCommandOptionsString() {
        $optionPairs = array();
        
        foreach ($this->getCommandOptions() as $key => $value) {
            $optionPairs[] = $key . '=' . $value;
        }
        
        return implode(' ', $optionPairs);
    }
    
    
    
    /**
     * 
     * @return array
     */
    private function getCommandOptions() {
        $options = array(
            'output' => 'json'
        );
        
        if ($this->hasDocumentCharacterSet()) {
            $options['charset'] = $this->getDocumentCharacterSet();
        }
        
        return $options;        
    }
    
    
    
}