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

use RestControl\ApiClient\ApiClientRequest;
use RestControl\TestCase\ChainTrait;
use RestControl\TestCase\ResponseFiltersBag;
use RestControl\TestCasePipeline\Adapters\ApiClientRequestAdapter;
use Psr\Log\InvalidArgumentException;

/**
 * Class RunTestObjectsStage
 *
 * @package RestControl\TestCasePipeline
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
     * @var null|TestCasePipelineListener
     */
    protected $pipelineListener;

    /**
     * RunTestObjectsStage constructor.
     *
     * @param ResponseFiltersBag       $responseFiltersBag
     * @param TestCasePipelineListener $listener
     */
    public function __construct(
        ResponseFiltersBag $responseFiltersBag,
        TestCasePipelineListener $listener = null
    ){
        $this->apiClientRequestAdapter = new ApiClientRequestAdapter();
        $this->responseFiltersBag      = $responseFiltersBag;
        $this->pipelineListener        = $listener;
    }

    /**
     * @param TestCasePipelineListener $listener
     */
    public function setPipelineListener(TestCasePipelineListener $listener)
    {
        $this->pipelineListener = $listener;
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

            $this->runTestsObject($payload, $testsObject);

            if($this->pipelineListener) {
                $this->pipelineListener->afterTestCaseResult($testsObject);
            }
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

            $errors = $this->responseFiltersBag->filterResponse(
                $apiClientResponse,
                $this->__getResponseChain($testObject->getRequestChain())
            );

            if(!empty($errors)) {
                foreach($errors as $error){
                    $testObject->addException($error);
                }
            }

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
            new $className(),
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