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
use Psr\Log\InvalidArgumentException;
use RestControl\Loader\TestsBag;

/**
 * Class Payload
 */
class Payload
{
    /**
     * @var TestsBag
     */
    protected $testsBag;

    /**
     * @var string
     */
    protected $testsTags;

    /**
     * @var ApiClientInterface
     */
    protected $apiClient;

    /**
     * @var array
     */
    protected $testsSuiteObjects = [];

    /**
     * Payload constructor.
     *
     * @param ApiClientInterface $apiClient
     * @param TestsBag           $testsBag
     * @param string             $testsTags
     */
    public function __construct(
        ApiClientInterface $apiClient,
        TestsBag $testsBag,
        $testsTags = ''
    ){
        $this->apiClient = $apiClient;
        $this->testsBag  = $testsBag;
        $this->testsTags = (string) $testsTags;
    }

    /**
     * @param TestSuiteObject $suiteObject
     */
    public function addTestSuiteObject(TestSuiteObject $suiteObject)
    {
        $this->testsSuiteObjects []= $suiteObject;
    }

    /**
     * @param array $objects
     */
    public function setTestSuiteObjects(array $objects)
    {
        foreach($objects as $object) {

            if(!$object instanceof TestSuiteObject) {
                throw new InvalidArgumentException('Object must be instance of ' . TestSuiteObject::class . '.');
            }

            $this->addTestSuiteObject($object);
        }
    }

    /**
     * @return array
     */
    public function getTestsSuiteObjects()
    {
        return $this->testsSuiteObjects;
    }

    /**
     * @return ApiClientInterface
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }

    /**
     * @return TestsBag
     */
    public function getTestsBag()
    {
        return $this->testsBag;
    }

    /**
     * @return string
     */
    public function getTestsTag()
    {
        return $this->testsTags;
    }
}