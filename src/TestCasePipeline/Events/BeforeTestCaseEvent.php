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

use RestControl\TestCasePipeline\TestObject;
use Symfony\Component\EventDispatcher\Event;

class BeforeTestCaseEvent extends Event
{
    const NAME = 'before.testCase';

    /**
     * @var TestObject
     */
    protected $testObject;

    /**
     * BeforeTestCaseEvent constructor.
     *
     * @param TestObject $testObject
     */
    public function __construct(TestObject $testObject)
    {
        $this->testObject = $testObject;
    }

    /**
     * @return TestObject
     */
    public function getTestObject()
    {
        return $this->testObject;
    }
}