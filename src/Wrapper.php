<?php

namespace webignition\HtmlValidator\Wrapper;

use webignition\HtmlValidator\Output\Output;
use webignition\HtmlValidator\Output\Parser\Parser;

class Wrapper
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
     * @param array $configurationValues
     */
    public function createConfiguration($configurationValues)
    {
        if (!isset($configurationValues[self::CONFIG_KEY_DOCUMENT_URI])) {
            throw new \InvalidArgumentException(
                sprintf('Configuration value "%s" not set', self::CONFIG_KEY_DOCUMENT_URI),
                1
            );
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
     * @return Output
     */
    public function validate()
    {
        if (empty($this->documentUri)) {
            throw new \InvalidArgumentException(
                sprintf('Configuration value "%s" not set', self::CONFIG_KEY_DOCUMENT_URI),
                2
            );
        }

        $parser = new Parser();
        return $parser->parse(
            shell_exec($this->getExecutableCommand())
        );
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
