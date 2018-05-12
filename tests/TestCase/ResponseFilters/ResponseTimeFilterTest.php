<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase\ResponseFilters;

use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ExpressionLanguage\Expression;
use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ResponseFilters\ResponseTimeFilter;

class ResponseTimeFilterTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('responseTime', (new ResponseTimeFilter())->getName());
    }

    public function testValidParams()
    {
        $filter = new ResponseTimeFilter();

        $this->assertFalse($filter->validateParams(['asdf']));
        $this->assertFalse($filter->validateParams([12345.1233]));
        $this->assertFalse($filter->validateParams([12345]));
        $this->assertTrue($filter->validateParams([new Expression('equalsTo', [200])]));
    }

    public function testInvalidResponseTime()
    {
        $filter = new ResponseTimeFilter();
        $response = new ApiClientResponse(
            200,
            [],
            '',
            0,
            123
        );

        $expression = new Expression('equalsTo', [20000]);

        $filter->call($response, [$expression]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertEmpty($statsCollector->getErrors());

        $this->assertSame(
            [
                [
                    'filter'        => $filter,
                    'errorCode'     => ResponseTimeFilter::ERROR_RESPONSE_TIME_MISMATCH,
                    'givenValue'    => 123,
                    'expectedValue' => $expression,
                ],
            ],
            $statsCollector->getFilterErrors()
        );
    }

    public function testValidResponseTime()
    {
        $filter = new ResponseTimeFilter();
        $response = new ApiClientResponse(
            200,
            [],
            '',
            0,
            123
        );

        $filter->call($response, [new Expression('equalsTo', [123])]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertEmpty($statsCollector->getFilterErrors());
        $this->assertEmpty($statsCollector->getErrors());
    }
}