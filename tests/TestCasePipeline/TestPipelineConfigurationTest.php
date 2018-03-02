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
                'namespace'    => 'Sample\Namespace',
                'path'         => 'sample',
                'classSuffix'  => 'Test.php',
                'methodPrefix' => 'sample',
            ],
            'apiClient' => 'Sample\ApiClient\Namespace',
            'responseFilters' => [
                'Sample\Class\Namespace',
                'Another\Class\Namespace',
            ],
        ]);

        $this->assertSame([
            'namespace'    => 'Sample\Namespace',
            'path'         => 'sample',
            'classSuffix'  => 'Test.php',
            'methodPrefix' => 'sample',
        ], $configuration->getTestsNamespace());

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
        $configuration = new TestPipelineConfiguration([
            'tests' => [
                'namespace' => 'Sample\\',
                'path'      => 'sample',
            ],
        ]);

        $this->assertSame([
            'namespace'    => 'Sample\\',
            'path'         => 'sample',
            'classSuffix'  => '.php',
            'methodPrefix' => 'test',
        ], $configuration->getTestsNamespace());

        $this->assertSame(
            HttpGuzzleClient::class,
            $configuration->getApiClient()
        );

        $this->assertEmpty($configuration->getResponseFilters());
    }

    public function testApiMockResponses()
    {
        $configuration = new TestPipelineConfiguration([
            'tests' => [
                'namespace' => 'Sample\\',
                'path'      => 'sample',
            ],
            'apiMockResponses' => [
                'Sample\Class',
                'Another\Class',
            ],
        ]);

        $this->assertSame([
            'Sample\Class',
            'Another\Class',
        ], $configuration->getApiMockResponses());
    }

    public function testVariables()
    {
        $configuration = new TestPipelineConfiguration([
            'tests' => [
                'namespace' => 'Sample\\',
                'path'      => 'sample',
            ],
            'variables' => [
                'sample'  => 'value',
                'sample2' => [
                    'sample',
                    'sample2',
                ],
            ],
        ]);

        $this->assertSame([
            'sample'  => 'value',
            'sample2' => [
                'sample',
                'sample2',
            ],
        ], $configuration->getVariables());

        $this->assertSame(
            HttpGuzzleClient::class,
            $configuration->getApiClient()
        );

        $this->assertEmpty($configuration->getResponseFilters());
    }
}