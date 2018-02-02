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
use RestControl\TestCasePipeline\Events\AfterTestCaseEvent;
use RestControl\TestCasePipeline\TestObject;

class AfterTestCaseEventTest extends TestCase
{
    public function testEvent()
    {
        $testObject = $this->getMockBuilder(TestObject::class)
            ->disableOriginalConstructor()
            ->getMock();

        $event = new AfterTestCaseEvent($testObject);

        $this->assertSame('after.testCase', AfterTestCaseEvent::NAME);
        $this->assertInstanceOf(TestObject::class, $event->getTestObject());
    }
}

