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

use RestControl\ApiClient\HttpGuzzleClient;

/**
 * Class TestPipelineConfiguration
 *
 * @package RestControl\TestCasePipeline
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
        $this->configuration = $configuration;
    }

    /**
     * @return array
     */
    public function getTestsNamespaces()
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
}