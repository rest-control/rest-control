<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Console\Commands;

use Psr\Log\InvalidArgumentException;
use RestControl\TestCasePipeline\TestPipelineConfiguration;
use Symfony\Component\Yaml\Yaml;

trait HelpersTrait
{
    /**
     * Prepare pipeline configuration object.
     *
     * @return TestPipelineConfiguration
     */
    protected function resolveConfiguration()
    {
        $configurationPath = getcwd() . DIRECTORY_SEPARATOR . 'rest-control.yml';

        if(!is_string($configurationPath)) {
            throw new InvalidArgumentException('Configuration path must be a string.');
        }

        if(!file_exists($configurationPath)) {
            return new TestPipelineConfiguration([]);
        }

        if(!is_readable($configurationPath)) {
            throw new InvalidArgumentException('Configuration file['.$configurationPath.'] does not have read permission.');
        }

        $configuration = $this->loadConfiguration($configurationPath);
        $config = Yaml::parse($configuration);

        if(!is_array($config)) {
            throw new InvalidArgumentException('Configuration file wrong format.');
        }

        return new TestPipelineConfiguration($config);
    }

    /**
     * @param string $path
     *
     * @return bool|mixed|string
     */
    protected function loadConfiguration($path)
    {
        $variables = [
            'FILE_DIR' => dirname($path),
        ];

        $file = file_get_contents($path);

        foreach($variables as $name => $value) {
            $file = str_replace('{{' . $name . '}}', $value, $file);
        }

        return $file;
    }
}