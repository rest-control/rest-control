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
use RestControl\ApiClient\ApiClientInterface;
use RestControl\ApiClient\ApiClientRequest;
use RestControl\TestCase\ChainTrait;
use RestControl\TestCase\ResponseFiltersBag;
use RestControl\TestCase\TestCaseEventsInterface;
use RestControl\TestCasePipeline\Adapters\ApiClientRequestAdapter;
use Psr\Log\InvalidArgumentException;
use RestControl\TestCasePipeline\Events\AfterTestCaseEvent;
use RestControl\TestCasePipeline\Events\AfterTestsSuiteEvent;
use RestControl\TestCasePipeline\Events\BeforeTestCaseEvent;
use RestControl\TestCasePipeline\Events\BeforeTestsSuiteEvent;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\TestObject;
use RestControl\TestCasePipeline\TestSuiteObject;
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
        $suiteObjects = $payload->getTestsSuiteObjects();
        $testCaseId   = 1;

        foreach($suiteObjects as $suite) {
            /** @var TestSuiteObject $suite */
            $this->runBeforeSuiteEvents($suite);

            $testCaseId = $this->runSuite($suite, $payload, $testCaseId);

            $this->runAfterSuiteEvents($suite);
        }

        return $payload;
    }

    /**
     * @param TestSuiteObject $suite
     * @param Payload         $payload
     * @param int             $testCaseId
     *
     * @return int
     */
    protected function runSuite(TestSuiteObject $suite, Payload $payload, $testCaseId)
    {
        foreach($suite->getTestsObjects() as $testsObject) {
            /** @var TestObject $testsObject */
            $testsObject->setQueueIndex($testCaseId);

            $this->eventDispatcher->dispatch(
                BeforeTestCaseEvent::NAME,
                new BeforeTestCaseEvent($testsObject)
            );

            $this->runTestsObject(
                $payload->getApiClient(),
                $testsObject
            );

            $this->eventDispatcher->dispatch(
                AfterTestCaseEvent::NAME,
                new AfterTestCaseEvent($testsObject)
            );

            $testCaseId++;
        }

        return $testCaseId;
    }

    /**
     * @param TestSuiteObject $suite
     */
    protected function runBeforeSuiteEvents(TestSuiteObject $suite)
    {
        $this->eventDispatcher->dispatch(
            BeforeTestsSuiteEvent::NAME,
            new BeforeTestsSuiteEvent($suite)
        );

        $object = $suite->getSuite();

        if(!$object instanceof TestCaseEventsInterface) {
            return;
        }

        $object->__beforeTests();
    }

    /**
     * @param TestSuiteObject $suite
     */
    protected function runAfterSuiteEvents(TestSuiteObject $suite)
    {
        $this->eventDispatcher->dispatch(
            AfterTestsSuiteEvent::NAME,
            new BeforeTestsSuiteEvent($suite)
        );

        $object = $suite->getSuite();

        if(!$object instanceof TestCaseEventsInterface) {
            return;
        }

        $object->__afterTests();
    }

    /**
     * @param ApiClientInterface $apiClient
     * @param TestObject         $testObject
     */
    protected function runTestsObject(ApiClientInterface $apiClient, TestObject $testObject)
    {
        $apiClientRequest = $this->prepareApiClientRequest($testObject);

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
        $delegateObject = $testObject->getTestSuiteObject()->getSuite();
        $delegate       = $testObject->getDelegate();

        if(!$delegate) {
            throw new InvalidArgumentException('TestObject must have Delegate');
        }

        $chain = call_user_func([
            $delegateObject,
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