<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\ApiClient;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Log\InvalidArgumentException;
use RestControl\ApiClient\ApiClientRequest;
use RestControl\ApiClient\ApiClientResponse;
use RestControl\ApiClient\MockApiClient;
use RestControl\TestCasePipeline\TestPipelineConfiguration;
use RestControl\Utils\MockApiResponseInterface;

class MockApiClientTest extends TestCase
{
    public function testInvalidMockedResponses()
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
                          ->getMockForAbstractClass();

        $configuration = $this->getMockBuilder(TestPipelineConfiguration::class)
                              ->disableOriginalConstructor()
                              ->setMethods(['getApiMockResponses'])
                              ->getMockForAbstractClass();

        $configuration->expects($this->once())
                      ->method('getApiMockResponses')
                      ->willReturn([
                          '\stdClass',
                      ]);

        $this->expectException(InvalidArgumentException::class);

        new MockApiClient(
            $configuration,
            $container
        );
    }

    public function testInvalidUrlMockedResponses()
    {
        $mockResponse = $this->getMockBuilder(MockApiResponseInterface::class)
                             ->setMethods(['getUrl'])
                             ->getMockForAbstractClass();
        $mockResponse->expects($this->once())
                     ->method('getUrl')
                     ->willReturn('INVALID URL');

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();
        $container->expects($this->once())
                  ->method('get')
                  ->with(MockApiResponseInterface::class)
                  ->willReturn($mockResponse);

        $configuration = $this->getMockBuilder(TestPipelineConfiguration::class)
            ->disableOriginalConstructor()
            ->setMethods(['getApiMockResponses'])
            ->getMockForAbstractClass();
        $configuration->expects($this->once())
            ->method('getApiMockResponses')
            ->willReturn([
                MockApiResponseInterface::class,
            ]);

        $this->expectException(InvalidArgumentException::class);

        new MockApiClient(
            $configuration,
            $container
        );
    }

    public function testInvalidResponseMockedResponses()
    {
        $mockResponse = $this->getMockBuilder(MockApiResponseInterface::class)
            ->setMethods(['getUrl'])
            ->getMockForAbstractClass();
        $mockResponse->expects($this->once())
            ->method('getUrl')
            ->willReturn('INVALID URL');

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();
        $container->expects($this->once())
            ->method('get')
            ->with(MockApiResponseInterface::class)
            ->willReturn($mockResponse);

        $configuration = $this->getMockBuilder(TestPipelineConfiguration::class)
            ->disableOriginalConstructor()
            ->setMethods(['getApiMockResponses'])
            ->getMockForAbstractClass();
        $configuration->expects($this->once())
            ->method('getApiMockResponses')
            ->willReturn([
                MockApiResponseInterface::class,
            ]);

        $this->expectException(InvalidArgumentException::class);

        new MockApiClient(
            $configuration,
            $container
        );
    }

    public function testMockedResponsesRouteInvalidApiResponse()
    {
        $mockResponse = $this->getMockBuilder(MockApiResponseInterface::class)
            ->setMethods(['getUrl', 'getApiClientResponse'])
            ->getMockForAbstractClass();
        $mockResponse->expects($this->once())
            ->method('getUrl')
            ->willReturn('GET::http://sample.domain/sample');
        $mockResponse->expects($this->once())
                     ->method('getApiClientResponse')
                     ->willReturn(null);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();
        $container->expects($this->once())
            ->method('get')
            ->with(MockApiResponseInterface::class)
            ->willReturn($mockResponse);

        $configuration = $this->getMockBuilder(TestPipelineConfiguration::class)
            ->disableOriginalConstructor()
            ->setMethods(['getApiMockResponses'])
            ->getMockForAbstractClass();
        $configuration->expects($this->once())
            ->method('getApiMockResponses')
            ->willReturn([
                MockApiResponseInterface::class,
            ]);

        $schema = new ApiClientRequest();
        $schema->setUrl('http://sample.domain/sample');
        $schema->setMethod('get');

        $mockClient = new MockApiClient(
            $configuration,
            $container
        );

        $this->expectException(InvalidArgumentException::class);

        $mockClient->send($schema);
    }

    public function testMockedResponsesRoute()
    {
        $apiResponse = new ApiClientResponse(200, [], '');

        $mockResponse = $this->getMockBuilder(MockApiResponseInterface::class)
            ->setMethods(['getUrl', 'getApiClientResponse'])
            ->getMockForAbstractClass();
        $mockResponse->expects($this->once())
            ->method('getUrl')
            ->willReturn('GET::http://sample.domain/sample');
        $mockResponse->expects($this->once())
            ->method('getApiClientResponse')
            ->willReturn($apiResponse);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->setMethods(['get'])
            ->getMockForAbstractClass();
        $container->expects($this->once())
            ->method('get')
            ->with(MockApiResponseInterface::class)
            ->willReturn($mockResponse);

        $configuration = $this->getMockBuilder(TestPipelineConfiguration::class)
            ->disableOriginalConstructor()
            ->setMethods(['getApiMockResponses'])
            ->getMockForAbstractClass();
        $configuration->expects($this->once())
            ->method('getApiMockResponses')
            ->willReturn([
                MockApiResponseInterface::class,
            ]);

        $schema = new ApiClientRequest();
        $schema->setUrl('http://sample.domain/sample');
        $schema->setMethod('get');

        $mockClient = new MockApiClient(
            $configuration,
            $container
        );

        $this->assertSame(
            $apiResponse,
            $mockClient->send($schema)
        );
    }
}