<?php

namespace JekyllScout\Config;

use InvalidArgumentException;
use Symfony\Component\Yaml\Yaml;

class ConfigReader
{
    /**
     * @param string $path
     *
     * @return Config
     *
     * @throws InvalidArgumentException When the config file cannot be read
     */
    public function read($path)
    {
        $configPath = rtrim($path, '/').'/.jekyllscout.yml';
        if (!file_exists($configPath)) {
            throw new InvalidArgumentException(sprintf('The config file "%s" could not be read.', $configPath));
        }

        return new Config(Yaml::parse(file_get_contents($configPath)));
    }
}
