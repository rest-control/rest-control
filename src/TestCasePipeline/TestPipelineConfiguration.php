<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline;

use Psr\Log\InvalidArgumentException;
use RestControl\ApiClient\HttpGuzzleClient;

/**
 * Class TestPipelineConfiguration
 */
class TestPipelineConfiguration
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * TestPipelineConfiguration constructor.
     *
     * @param array $configuration
     */
    public function __construct(array $configuration = [])
    {
        $this->setConfiguration($configuration);
    }

    /**
     * @return array
        <pre>
            [
                'namespace'     => string, //classes namespace
                'path'          => string, //path to TestCases
                'classSuffix'   => string, //suffix for TestCase classes
                'methodPrefix'  => string, //prefix for TestCase methods
            ]
        </pre>
     */
    public function getTestsNamespace()
    {
        return (array) $this->get('tests', []);
    }

    /**
     * @return array
     */
    public function getResponseFilters()
    {
        return (array) $this->get('responseFilters', []);
    }

    /**
     * @return mixed
     */
    public function getApiClient()
    {
       return $this->get('apiClient', HttpGuzzleClient::class);
    }

    /**
     * @return array
     */
    public function getApiMockResponses()
    {
        return $this->get('apiMockResponses', []);
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->get('variables', []);
    }

    /**
     * @param string $index
     * @param mixed  $default
     *
     * @return mixed
     */
    protected function get($index, $default = null)
    {
        if(isset($this->configuration[$index])) {
            return $this->configuration[$index];
        }

        return $default;
    }

    /**
     * @param array $configuration
     */
    protected function setConfiguration(array $configuration)
    {
        if(!isset($configuration['tests']) || !is_array($configuration['tests'])) {
            throw new InvalidArgumentException('You must provide "tests" configuration.');
        }

        $this->setTestsConfiguration($configuration['tests']);

        if(isset($configuration['responseFilters']) && is_array($configuration['responseFilters'])) {
            $this->setResponseFilters($configuration['responseFilters']);
        }

        if(isset($configuration['apiClient']) && is_string($configuration['apiClient'])) {
            $this->configuration['apiClient'] = $configuration['apiClient'];
        }

        if(isset($configuration['apiMockResponses']) && is_array($configuration['apiMockResponses'])) {
            $this->configuration['apiMockResponses'] = $configuration['apiMockResponses'];
        }

        if(isset($configuration['variables']) && is_array($configuration['variables'])) {
            $this->configuration['variables'] = $configuration['variables'];
        }
    }

    /**
     * @param array $configuration
     */
    protected function setResponseFilters(array $configuration)
    {
        foreach($configuration as $filter) {
            if(!is_string($filter)) {
                throw new InvalidArgumentException('Filter class must be a string.');
            }
        }

        $this->configuration['responseFilters'] = $configuration;
    }

    /**
     * @param array $configuration
     */
    protected function setTestsConfiguration(array $configuration)
    {
        if(!isset($configuration['namespace']) || !is_string($configuration['namespace'])) {
            throw new InvalidArgumentException('Configuration namespace must be a string.');
        }

        if(!isset($configuration['path']) || !is_string($configuration['path'])) {
            throw new InvalidArgumentException('Configuration path must be a string.');
        }

        if(!isset($configuration['classSuffix']) || !is_string($configuration['classSuffix'])) {
            $configuration['classSuffix'] = '.php';
        }

        if(!isset($configuration['methodPrefix']) || !is_string($configuration['methodPrefix'])) {
            $configuration['methodPrefix'] = 'test';
        }

        $this->configuration['tests'] = $configuration;
    }
}