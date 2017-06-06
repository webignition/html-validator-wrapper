<?php

namespace webignition\HtmlValidator\Wrapper;

class Configuration
{
    const DEFAULT_VALIDATOR_PATH = '/usr/local/validator/cgi-bin/check';
    const CONFIG_KEY_VALIDATOR_PATH = 'validator-path';
    const CONFIG_KEY_DOCUMENT_URI = 'document-uri';
    const CONFIG_KEY_DOCUMENT_CHARACTER_SET = 'document-character-set';

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
     * @see http://en.wikipedia.org/wiki/Character_encodings_in_HTML
     * @var string
     */
    private $documentCharacterSet = null;

    /**
     * @param $configurationValues
     */
    public function __construct($configurationValues)
    {
        if (!isset($configurationValues[self::CONFIG_KEY_DOCUMENT_URI])) {
            throw new \InvalidArgumentException('Configuration value "documentUri" not set', 1);
        }

        if (!isset($configurationValues[self::CONFIG_KEY_VALIDATOR_PATH])) {
            $configurationValues[self::CONFIG_KEY_VALIDATOR_PATH] = self::DEFAULT_VALIDATOR_PATH;
        }

        if (!isset($configurationValues[self::CONFIG_KEY_DOCUMENT_CHARACTER_SET])) {
            $configurationValues[self::CONFIG_KEY_DOCUMENT_CHARACTER_SET] = null;
        }

        $this->validatorPath = $configurationValues[self::CONFIG_KEY_VALIDATOR_PATH];
        $this->documentUri = $configurationValues[self::CONFIG_KEY_DOCUMENT_URI];
        $this->documentCharacterSet = $configurationValues[self::CONFIG_KEY_DOCUMENT_CHARACTER_SET];
    }

    /**
     * @return string
     */
    public function getExecutableCommand()
    {
        return $this->validatorPath . ' ' . $this->getCommandOptionsString() . ' uri=' . $this->documentUri;
    }

    /**
     * @return string
     */
    private function getCommandOptionsString()
    {
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
    private function getCommandOptions()
    {
        $options = array(
            'output' => 'json'
        );

        if (!empty($this->documentCharacterSet)) {
            $options['charset'] = $this->documentCharacterSet;
        }

        return $options;
    }
}