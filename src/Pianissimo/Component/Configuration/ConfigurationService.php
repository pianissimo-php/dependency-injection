<?php

namespace App\Pianissimo\Component\Configuration;

use App\Pianissimo\Component\Configuration\Exception\ConfigurationFileException;
use Symfony\Component\Yaml\Yaml;

class ConfigurationService
{
    /**
     * @throws ConfigurationFileException
     */
    public function load(): Configuration
    {
        $data = Yaml::parseFile(getRootDirectory() . '../../config/config.yaml');

        $data = $this->ensureSettings($this->getCriteria(), $data);
        return new Configuration($data);
    }

    /**
     * Criteria for the configuration
     */
    private function getCriteria(): array
    {
        return [
            'environment' => [
                'required' => true,
                'type' => 'string',
            ],
            'controllers' => [
                'required' => false,
                'type' => 'array',
                'default' => [],
            ],
            'routes' => [
                'required' => false,
                'type' => 'array',
                'default' => [],
            ],
        ];
    }

    /**
     * Validates the settings. Makes sure that they exists and have the right types.
     * Throws an error if the setting is required and doesn't exist.
     * Sets the 'default' option as value if the setting does not exist (and is not required)
     *
     * @throws ConfigurationFileException
     */
    private function ensureSettings(array $settings, array $data): array
    {
        foreach ($settings as $setting => $options) {
            // Throw an Exception if the setting is required and doesn't exist
            if (isset($data[$setting]) === false && $options['required'] === true) {
                throw new ConfigurationFileException(sprintf("Missing required setting '%s'", $setting));
            }

            // Throw an Exception if the setting has not the right type
            if ((isset($data[$setting]) === true) && gettype($data[$setting]) !== $options['type']) {
                throw new ConfigurationFileException(sprintf("Setting '%s' has type '%s', must be type of '%s'", $setting, gettype($data[$setting]), $options['type']));
            }

            // Set the default setting if the setting doesn't exist
            if (isset($data[$setting]) === false) {
                $data[$setting] = $options['default'];
            }
        }

        return $data;
    }
}