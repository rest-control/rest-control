<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCasePipeline\Events;

use PHPUnit\Framework\TestCase;
use RestControl\TestCasePipeline\Events\AfterTestsSuiteEvent;
use RestControl\TestCasePipeline\TestSuiteObject;

class AfterTestsSuiteEventTest extends TestCase
{
    public function testEvent()
    {
        $testSuiteObject = $this->getMockBuilder(TestSuiteObject::class)
            ->disableOriginalConstructor()
            ->getMock();

        $event = new AfterTestsSuiteEvent($testSuiteObject);

        $this->assertSame('after.testsSuite', AfterTestsSuiteEvent::NAME);
        $this->assertInstanceOf(TestSuiteObject::class, $event->getTestSuiteObject());
    }
}

