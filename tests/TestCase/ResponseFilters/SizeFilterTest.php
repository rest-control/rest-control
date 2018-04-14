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
use RestControl\TestCase\ResponseFilters\SizeFilter;
use RestControl\TestCase\StatsCollector\StatsCollector;

class SizeFilterTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('size', (new SizeFilter())->getName());
    }

    public function testStatsCollector()
    {
        $filter = new SizeFilter();

        $statsCollector = new StatsCollector();
        $statsCollector->addAssertionsCount(22);

        $filter->setStatsCollection($statsCollector);

        $filterStatsCollector = $filter->getStatsCollector();

        $this->assertSame($statsCollector, $filterStatsCollector);
        $this->assertSame(22, $filterStatsCollector->getAssertionsCount());
    }

    public function testValidateParams()
    {
        $filter = new SizeFilter();

        $this->assertFalse($filter->validateParams(['sample']));
        $this->assertTrue($filter->validateParams([11111]));
    }

    public function testInvalidSizeSimpleValue()
    {
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '',
            1000
        );

        $filter = new SizeFilter();
        $filter->call($apiClientResponse, [20]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => SizeFilter::ERROR_INVALID_VALUE,
                'givenValue'    => 1000,
                'expectedValue' => 20,
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testInvalidSizeExpression()
    {
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '',
            1000
        );

        $expression = new Expression('lessThan', [20]);

        $filter = new SizeFilter();
        $filter->call($apiClientResponse, [$expression]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => SizeFilter::ERROR_INVALID_VALUE,
                'givenValue'    => 1000,
                'expectedValue' => $expression,
            ],
        ], $statsCollector->getFilterErrors());
    }
}