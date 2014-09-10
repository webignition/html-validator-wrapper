<?php

namespace webignition\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Wrapper\Configuration\Configuration;
use webignition\HtmlValidator\Output\Parser\Parser;

class Wrapper {
    
    
    /**
     *
     * @var Configuration
     */
    private $configuration;
    
    
    /**
     * 
     * @param \webignition\HtmlValidator\Wrapper\Configuration\Configuration $configuration
     * @return \webignition\HtmlValidator\Wrapper\Wrapper
     */
    public function setConfiguration(Configuration $configuration) {
        $this->configuration = $configuration;
        return $this;
    }
    
    /**
     * 
     * @param array $configurationValues
     * @return \webignition\HtmlValidator\Wrapper\Wrapper
     * @throws \InvalidArgumentException
     */
    public function createConfiguration($configurationValues) {
        if (!is_array($configurationValues) || empty($configurationValues)) {
            throw new \InvalidArgumentException('A non-empty array of configuration values must be passed to create configuration', 2);
        }
        
        if (!isset($configurationValues['documentUri'])) {
            throw new \InvalidArgumentException('Configruation value "documentUri" not set', 3);
        }
        
        $configuration = new Configuration();
        $configuration->setDocumentUri($configurationValues['documentUri']);
        
        if (isset($configurationValues['validatorPath'])) {
            $configuration->setValidatorPath($configurationValues['validatorPath']);
        }
        
        if (isset($configurationValues['documentCharacterSet'])) {
            $configuration->setDocumentCharacterSet($configurationValues['documentCharacterSet']);
        }           
        
        $this->setConfiguration($configuration);
        return $this;
    }
    
    
    /**
     * 
     * @return Configuration
     */
    public function getConfiguration() {
        return $this->configuration;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasConfiguration() {
        return !is_null($this->getConfiguration());
    }
    
    
    public function validate() {
        if (!$this->hasConfiguration()) {
            throw new \InvalidArgumentException('Unable to validate; configuration not set', 1);
        }
        
        $parser = new Parser();
        return $parser->parse(implode("\n", $this->getRawValidatorOutputLines()));        
    }
    
    
    /**
     * 
     * @return array
     */
    public function getRawValidatorOutputLines() {
        $validatorOutputLines = array();
        exec($this->getConfiguration()->getExecutableCommand(), $validatorOutputLines);        
        return $validatorOutputLines;
    }
    
}