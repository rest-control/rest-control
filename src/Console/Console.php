<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Console;

use Composer\Autoload\ClassLoader;
use Psr\Log\InvalidArgumentException;

/**
 * Class Console
 * @package RestControl\Console
 */
class Console
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var ClassLoader
     */
    protected $classLoader;

    /**
     * Console constructor.
     *
     * @param ClassLoader $classLoader
     * @param string      $configurationPath
     */
    public function __construct(
        ClassLoader $classLoader,
        $configurationPath
    ){
        $this->classLoader = $classLoader;
        $this->loadConfiguration($configurationPath);
    }

    /**
     * Run console application.
     * @todo
     * @return int
     */
    public function run()
    {
        echo 'Hello world !' . "\n";
        return 0;
    }

    /**
     * @param $configurationPath
     */
    protected function loadConfiguration($configurationPath)
    {
        if(!is_string($configurationPath)) {
            throw new InvalidArgumentException('Configuration path must be a string.');
        }

        if(!file_exists($configurationPath) || !is_readable($configurationPath)) {
            throw new InvalidArgumentException('Configuration file[' . $configurationPath . '] does not have read permission or does not exists.');
        }

        $config = require $configurationPath;

        if(!is_array($config)) {
            throw new InvalidArgumentException('Configuration file wrong format.');
        }

        $this->configuration = $config;
    }
}