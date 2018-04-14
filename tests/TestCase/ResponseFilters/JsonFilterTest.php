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
use RestControl\TestCase\ResponseFilters\JsonFilter;
use PHPUnit\Framework\TestCase;
use RestControl\TestCase\StatsCollector\StatsCollector;

class JsonFilterTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('json', (new JsonFilter())->getName());
    }

    public function testStatsCollector()
    {
        $filter = new JsonFilter();

        $statsCollector = new StatsCollector();
        $statsCollector->addAssertionsCount(22);

        $filter->setStatsCollection($statsCollector);

        $filterStatsCollector = $filter->getStatsCollector();

        $this->assertSame($statsCollector, $filterStatsCollector);
        $this->assertSame(22, $filterStatsCollector->getAssertionsCount());
    }

    public function testValidateParams()
    {
        $filter = new JsonFilter();

        $this->assertFalse($filter->validateParams(['sample']));
        $this->assertFalse($filter->validateParams([111]));
        $this->assertTrue($filter->validateParams([]));
        $this->assertTrue($filter->validateParams([false]));
        $this->assertTrue($filter->validateParams([true]));
    }

    public function testCheckContentTypeWithoutExpectedBody()
    {
        $filter = new JsonFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [
                'Content-Type' => [
                    'another content type',
                    'application/json;charset=utf-8',
                ],
            ],
            '',
            0
        );

        $filter->call($apiClientResponse, [true, true]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertFalse($statsCollector->hasErrors());
        $this->assertSame(2, $statsCollector->getAssertionsCount());
    }

    public function testInvalidCheckContentType()
    {
        $filter = new JsonFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [
                'Content-Type' => [
                    'another content type',
                    'application/wrongjson;charset=utf-8',
                ],
            ],
            '',
            0
        );

        $filter->call($apiClientResponse);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(2, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter' => $filter,
                'errorCode' => JsonFilter::ERROR_WRONG_CONTENT_TYPE,
                'givenValue' => [
                    'another content type',
                    'application/wrongjson;charset=utf-8',
                ],
                'expectedValue' => '/application/json/',
            ],
            [
                'filter'        => $filter,
                'errorCode'     => JsonFilter::ERROR_INVALID_BODY,
                'givenValue'    => '',
                'expectedValue' => 'array|json',
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testInvalidBody()
    {

        $filter = new JsonFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '',
            0
        );

        $filter->call($apiClientResponse, [false]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => JsonFilter::ERROR_INVALID_BODY,
                'givenValue'    => '',
                'expectedValue' => 'array|json',
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testIgnoreContentTypeAndBody()
    {

        $filter = new JsonFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '',
            0
        );

        $filter->call($apiClientResponse, [false, true]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertFalse($statsCollector->hasErrors());
        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertEmpty($statsCollector->getFilterErrors());
    }
}