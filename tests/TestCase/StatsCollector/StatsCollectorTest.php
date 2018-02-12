<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase\StatsCollector;

use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ResponseFilters\FilterInterface;
use RestControl\TestCase\StatsCollector\EndContextException;
use RestControl\TestCase\StatsCollector\StatsCollector;

class StatsCollectorTest extends TestCase
{
    public function testFilterError()
    {
        $statsCollector = new StatsCollector();
        $filter = $this->getMockBuilder(FilterInterface::class)
                       ->getMockForAbstractClass();

        $statsCollector->filterError(
            $filter,
            123,
            'sampleValue',
            'expValue'
        );

        $this->assertSame(
            [
                [
                    'filter'        => $filter,
                    'errorCode'     => 123,
                    'givenValue'    => 'sampleValue',
                    'expectedValue' => 'expValue',
                ]
            ],
            $statsCollector->getFilterErrors()
        );
    }

    public function testEndContext()
    {
        $statsCollector = new StatsCollector();
        $this->assertInstanceOf(
            EndContextException::class,
            $statsCollector->endContext()
        );
    }

    public function testAssertionsCount()
    {
        $statsCollector = new StatsCollector();
        $statsCollector->addAssertionsCount()
                       ->addAssertionsCount();

        $this->assertSame(2, $statsCollector->getAssertionsCount());

        $statsCollector->addAssertionsCount(10);

        $this->assertSame(12, $statsCollector->getAssertionsCount());

        $statsCollector->reset();

        $this->assertSame(0, $statsCollector->getAssertionsCount());
    }

    public function testErrors()
    {

        $statsCollector = new StatsCollector();
        $statsCollector->error('Sample error !', [
            'my' => 'text',
        ]);
        $statsCollector->error('Sample error !2', [
            'my2' => 'text2',
        ]);

        $this->assertSame([
            [
                'message' => 'Sample error !',
                'context' => [
                    'my' => 'text',
                ],
            ],
            [
                'message' => 'Sample error !2',
                'context' => [
                    'my2' => 'text2',
                ],
            ]
        ], $statsCollector->getErrors());
    }
}