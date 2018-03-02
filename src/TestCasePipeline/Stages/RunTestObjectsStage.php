<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline\Stages;

use Psr\Container\ContainerInterface;
use RestControl\ApiClient\ApiClientRequest;
use RestControl\TestCase\ChainTrait;
use RestControl\TestCase\ResponseFiltersBag;
use RestControl\TestCasePipeline\Adapters\ApiClientRequestAdapter;
use Psr\Log\InvalidArgumentException;
use RestControl\TestCasePipeline\Events\AfterTestCaseEvent;
use RestControl\TestCasePipeline\Events\BeforeTestCaseEvent;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\TestObject;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class RunTestObjectsStage
 */
class RunTestObjectsStage
{
    use ChainTrait;

    /**
     * @var ApiClientRequestAdapter
     */
    protected $apiClientRequestAdapter;

    /**
     * @var ResponseFiltersBag
     */
    protected $responseFiltersBag;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * RunTestObjectsStage constructor.
     *
     * @param ResponseFiltersBag       $responseFiltersBag
     * @param EventDispatcherInterface $eventDispatcher
     * @param ContainerInterface       $container
     */
    public function __construct(
        ResponseFiltersBag $responseFiltersBag,
        EventDispatcherInterface $eventDispatcher,
        ContainerInterface $container
    ){
        $this->apiClientRequestAdapter = new ApiClientRequestAdapter();
        $this->responseFiltersBag      = $responseFiltersBag;
        $this->eventDispatcher         = $eventDispatcher;
        $this->container               = $container;
    }

    /**
     * @param Payload $payload
     *
     * @return Payload
     */
    public function __invoke(Payload $payload)
    {
        foreach($payload->getTestsObjects() as $i => $testsObject) {
            /** @var TestObject $testsObject */
            $testsObject->setQueueIndex($i);

            $this->eventDispatcher->dispatch(
                BeforeTestCaseEvent::NAME,
                new BeforeTestCaseEvent($testsObject)
            );

            $this->runTestsObject($payload, $testsObject);

            $this->eventDispatcher->dispatch(
                AfterTestCaseEvent::NAME,
                new AfterTestCaseEvent($testsObject)
            );
        }

        return $payload;
    }

    /**
     * @param Payload    $payload
     * @param TestObject $testObject
     */
    protected function runTestsObject(Payload $payload, TestObject $testObject)
    {
        $apiClientRequest = $this->prepareApiClientRequest($testObject);
        $apiClient        = $payload->getApiClient();

        try{

            $startRequest       = microtime(true);
            $apiClientResponse  = $apiClient->send($apiClientRequest);
            $elapsedRequestTime = microtime(true) - $startRequest;

            $testObject->setRequestTime($elapsedRequestTime);

            $statsCollector = $this->responseFiltersBag->filterResponse(
                $apiClientResponse,
                $this->__getResponseChain($testObject->getRequestChain())
            );

            $testObject->setStatsCollector($statsCollector);

        } catch (\Exception $e) {
            $testObject->addException($e);
        }
    }

    /**
     * @param TestObject $testObject
     *
     * @return ApiClientRequest
     */
    protected function prepareApiClientRequest(TestObject $testObject)
    {
        $delegate = $testObject->getDelegate();

        if(!$delegate) {
            throw new InvalidArgumentException('TestObject must have Delegate');
        }

        $className = $delegate->getClassName();

        $chain = call_user_func([
            $this->container->get($className),
            $delegate->getMethodName()
        ]);

        if(!$chain) {
            throw new InvalidArgumentException('TestCase['
                . $delegate->getClassName() . '@'
                . $delegate->getMethodName()
                .'] must returns chain.'
            );
        }

        $request = $this->__getRequestChain($chain);

        $testObject->setRequestChain($request);

        return $this->apiClientRequestAdapter->transform(
            $request
        );
    }
}