<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\ApiClient;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Psr\Container\ContainerInterface;
use Psr\Log\InvalidArgumentException;
use RestControl\TestCasePipeline\TestPipelineConfiguration;
use RestControl\Utils\MockApiResponseInterface;

class MockApiClient extends HttpGuzzleClient implements ApiClientInterface
{
    /**
     * @var Dispatcher
     */
    protected $routesDispatcher;

    public function __construct(
        TestPipelineConfiguration $testsConfiguration,
        ContainerInterface $container
    ){
        $this->setMockResponses($testsConfiguration, $container);
    }

    /**
     * @param ApiClientRequest $schema
     *
     * @return ApiClientResponse
     */
    public function send(ApiClientRequest $schema)
    {
        $routeInfo = $this->getResponseMock($schema);

        if(!$routeInfo) {
            return parent::send($schema);
        }

        $mock = $routeInfo[1]();

        if(!$mock instanceof MockApiResponseInterface) {
            return parent::send($schema);
        }

        $response = $mock->getApiClientResponse(
            $schema,
            $routeInfo[2] ?? []
        );

        if(!$response instanceof ApiClientResponse) {
            throw new InvalidArgumentException('Api mock response must be instance of ' . ApiClientResponse::class . '.');
        }

        return $response;
    }

    /**
     * @param ApiClientRequest $schema
     *
     * @return null|array
     */
    protected function getResponseMock(ApiClientRequest $schema)
    {
        $fullUrl = $this->getUrl($schema);
        $queryParams = $this->getQueryParams($schema);

        if(!empty($queryParams)) {
            $fullUrl .= '?' . http_build_query($queryParams);
        }

        if (false !== $pos = strpos($fullUrl, '?')) {
            $fullUrl = substr($fullUrl, 0, $pos);
        }

        $fullUrl = rawurldecode($fullUrl);

        $routeInfo = $this->routesDispatcher->dispatch(
            strtolower($schema->getMethod()),
            $fullUrl
        );

        if($routeInfo[0] !== Dispatcher::FOUND) {
            return null;
        }

        return $routeInfo;
    }

    /**
     * @param TestPipelineConfiguration $configuration
     * @param ContainerInterface        $container
     */
    private function setMockResponses(TestPipelineConfiguration $configuration, ContainerInterface $container)
    {
        $this->routesDispatcher = simpleDispatcher(function(RouteCollector $r) use($configuration, $container) {

            foreach($configuration->getApiMockResponses() as $i => $mockResponse) {

                $reflection = new \ReflectionClass($mockResponse);

                if(!$reflection->implementsInterface(MockApiResponseInterface::class)) {
                    throw new InvalidArgumentException('Class ' . $reflection->getName() . ' must implements ' . MockApiResponseInterface::class . '.');
                }

                /** @var MockApiResponseInterface $responseClass */
                $responseClass = $container->get($mockResponse);
                $routePath = explode('::', $responseClass->getUrl());

                if(!isset($routePath[0]) || !isset($routePath[1])) {
                    throw new InvalidArgumentException('Invalid route url format in ' . $reflection->getName());
                }

                $r->addRoute(strtolower($routePath[0]), $routePath[1], function() use ($responseClass) {
                    return $responseClass;
                });
            }
        });
    }
}