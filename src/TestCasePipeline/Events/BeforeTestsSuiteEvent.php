<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline\Events;

use RestControl\TestCasePipeline\TestSuiteObject;
use Symfony\Component\EventDispatcher\Event;

class BeforeTestsSuiteEvent extends Event
{
    const NAME = 'before.testsSuite';

    /**
     * @var TestSuiteObject
     */
    protected $testSuiteObject;

    /**
     * BeforeTestCaseEvent constructor.
     *
     * @param TestSuiteObject $testSuiteObject
     */
    public function __construct(TestSuiteObject $testSuiteObject)
    {
        $this->testSuiteObject = $testSuiteObject;
    }

    /**
     * @return TestSuiteObject
     */
    public function getTestSuiteObject()
    {
        return $this->testSuiteObject;
    }
}