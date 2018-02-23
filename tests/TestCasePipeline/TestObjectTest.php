<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCasePipeline;

use Psr\Log\InvalidArgumentException;
use RestControl\Loader\TestCaseDelegate;
use RestControl\TestCase\Request;
use RestControl\TestCase\StatsCollector\StatsCollectorInterface;
use RestControl\TestCasePipeline\TestObject;
use PHPUnit\Framework\TestCase;

class TestObjectTest extends TestCase
{
    public function testGetters()
    {
        $delegate = new TestCaseDelegate(
            'sample',
            'sample2'
        );

        $requestChain = new Request();

        $testObject = new TestObject($delegate);

        $this->assertSame('sample', $testObject->getDelegate()->getClassName());

        $testObject->setRequestTime(123.1234534636);
        $this->assertSame(123.1234534636, $testObject->getRequestTime());

        $testObject->setQueueIndex(35424);
        $this->assertSame(35424, $testObject->getQueueIndex());

        $this->assertSame(null, $testObject->getRequestChain());
        $testObject->setRequestChain($requestChain);
        $this->assertSame($requestChain, $testObject->getRequestChain());

        $this->assertEmpty($testObject->getExceptions());
        $this->assertFalse($testObject->hasErrors());
        $testObject->addException(new \Exception('Sample error'));
        $this->assertSame('Sample error', $testObject->getExceptions()[0]->getMessage());
        $this->assertTrue($testObject->hasErrors());

        $this->assertInstanceOf(StatsCollectorInterface::class, $testObject->getStatsCollector());
        $this->assertInstanceOf(StatsCollectorInterface::class, $testObject->getStatsCollector());
    }

    public function testSetterInvalidQueueIndex()
    {
        $delegate = new TestCaseDelegate(
            'sample',
            'sample2'
        );

        $testObject = new TestObject($delegate);
        $this->expectException(InvalidArgumentException::class);
        $testObject->setQueueIndex(new \stdClass());
    }
}