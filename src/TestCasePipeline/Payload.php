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

use RestControl\ApiClient\ApiClientInterface;
use RestControl\Loader\TestCaseDelegate;
use Psr\Log\InvalidArgumentException;

/**
 * Class Payload
 * @package RestControl\TestCasePipeline
 */
class Payload
{
    /**
     * @var array
     */
    protected $testsObjects = [];

    /**
     * @var ApiClientInterface
     */
    protected $apiClient;

    /**
     * Payload constructor.
     *
     * @param ApiClientInterface $apiClient
     * @param array              $testsDelegates
     */
    public function __construct(
        ApiClientInterface $apiClient,
        array $testsDelegates = []
    ){
        $this->apiClient = $apiClient;
        $this->setTestDelegates($testsDelegates);
    }

    /**
     * Returns array of TestObject.
     *
     * @return array
     */
    public function getTestsObjects()
    {
        return $this->testsObjects;
    }

    /**
     * @return ApiClientInterface
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * @param array $delegates
     */
    protected function setTestDelegates(array $delegates = [])
    {
        foreach($delegates as $delegate) {
            if(!$delegate instanceof TestCaseDelegate) {
                throw new InvalidArgumentException('Delegate must be instance of TestCaseDelegate.');
            }

            $this->testsObjects []= new TestObject($delegate);
        }
    }
}