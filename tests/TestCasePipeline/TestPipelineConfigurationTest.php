<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCasePipeline;

use PHPUnit\Framework\TestCase;
use RestControl\ApiClient\HttpGuzzleClient;
use RestControl\TestCasePipeline\TestPipelineConfiguration;

class TestPipelineConfigurationTest extends TestCase
{
    public function testGetters()
    {
        $configuration = new TestPipelineConfiguration([
            'tests' => [
                'Sample\Namespace',
            ],
            'apiClient' => 'Sample\ApiClient\Namespace',
            'responseFilters' => [
                'Sample\Class\Namespace',
                'Another\Class\Namespace',
            ],
        ]);

        $this->assertSame([
            'Sample\Namespace',
        ], $configuration->getTestsNamespaces());

        $this->assertSame(
            'Sample\ApiClient\Namespace',
            $configuration->getApiClient()
        );

        $this->assertSame([
            'Sample\Class\Namespace',
            'Another\Class\Namespace',
        ], $configuration->getResponseFilters());
    }

    public function testDefaultValues()
    {
        $configuration = new TestPipelineConfiguration([]);

        $this->assertEmpty($configuration->getTestsNamespaces());

        $this->assertSame(
            HttpGuzzleClient::class,
            $configuration->getApiClient()
        );

        $this->assertEmpty($configuration->getResponseFilters());
    }
}