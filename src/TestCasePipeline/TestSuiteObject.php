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

use Psr\Log\InvalidArgumentException;

class TestSuiteObject
{
    /**
     * @var array
     */
    protected $testObjects = [];

    /**
     * @var Object
     */
    protected $suite;

    /**
     * TestSuiteObject constructor.
     *
     * @param $suite
     */
    public function __construct($suite)
    {
        if(!is_object($suite)) {
            throw new InvalidArgumentException('Suite class must be an object.');
        }

        $this->suite = $suite;
    }

    /**
     * Automatically run setTestSuiteObject on $testObject.
     *
     * @param TestObject $testObject
     *
     * @return $this
     */
    public function addTestObject(TestObject $testObject)
    {
        $testObject->setTestSuiteObject($this);

        $this->testObjects []= $testObject;

        return $this;
    }

    /**
     * @param array $testObjects
     *
     * @return $this
     */
    public function addTestObjects(array $testObjects)
    {
        foreach($testObjects as $testObject) {

            if(!$testObject instanceof TestObject) {
                throw new InvalidArgumentException('TestObject must be instance of ' . TestObject::class);
            }

            $this->addTestObject($testObject);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getTestsObjects()
    {
        return $this->testObjects;
    }

    /**
     * @return Object
     */
    public function getSuite()
    {
        return $this->suite;
    }
}