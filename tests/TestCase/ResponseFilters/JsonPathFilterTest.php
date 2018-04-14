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
use RestControl\TestCase\ResponseFilters\JsonPathFilter;
use PHPUnit\Framework\TestCase;
use RestControl\TestCase\StatsCollector\EndContextException;

class JsonPathFilterTest extends TestCase
{

    public function testName()
    {
        $this->assertSame('jsonPath', (new JsonPathFilter())->getName());
    }

    public function testValidateParams()
    {
        $filter = new JsonPathFilter();

        $this->assertFalse($filter->validateParams([]));
        $this->assertFalse($filter->validateParams([111, 112]));
        $this->assertTrue($filter->validateParams(['sample', new Expression('equalsTo', [11])]));
        $this->assertTrue($filter->validateParams(['sample', function($value) { return true; }]));
    }

    public function testInvalidResponseBody()
    {
        $filter = new JsonPathFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            'invalid body format',
            123
        );

        try{
            $filter->call($apiClientResponse);
        } catch(EndContextException $e){

            $statsCollector = $filter->getStatsCollector();

            $this->assertTrue($statsCollector->hasErrors());
            $this->assertSame(1, $statsCollector->getAssertionsCount());
            $this->assertSame([
                [
                    'filter' => $filter,
                    'errorCode' => JsonPathFilter::ERROR_WRONG_BODY_FORMAT,
                    'givenValue' => 'invalid body format',
                    'expectedValue' => 'array|object|json',
                ],
            ], $statsCollector->getFilterErrors());
        }
    }

    public function testInvalidPath()
    {
        $filter = new JsonPathFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"test":{
                "sample": 1234
            }}',
            123
        );

        $expression = new Expression('equalsTo', [986785]);

        $filter->call($apiClientResponse, [
            '$.anotherIndex.sample',
            $expression
        ]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(2, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => JsonPathFilter::ERROR_INVALID_VALUE,
                'givenValue'    => null,
                'expectedValue' => $expression,
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testInvalidValue()
    {
        $filter = new JsonPathFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"test":{
                "sample": 1234
            }}',
            123
        );

        $expression = new Expression('equalsTo', [986785]);

        $filter->call($apiClientResponse, [
            '$.test.sample',
            $expression
        ]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(2, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => JsonPathFilter::ERROR_INVALID_VALUE,
                'givenValue'    => 1234,
                'expectedValue' => $expression,
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testInvalidValue2()
    {
        $filter = new JsonPathFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"test":{
                "sample": [
                    123,
                    2542565,
                    123,
                    5468768
                ]
            }}',
            123
        );

        $expression = new Expression('equalsTo', [123]);

        $filter->call($apiClientResponse, [
            '$.test.sample.*',
            $expression
        ]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(5, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => JsonPathFilter::ERROR_INVALID_VALUE,
                'givenValue'    => 2542565,
                'expectedValue' => $expression,
            ],
            [
                'filter'        => $filter,
                'errorCode'     => JsonPathFilter::ERROR_INVALID_VALUE,
                'givenValue'    => 5468768,
                'expectedValue' => $expression,
            ],
        ], $statsCollector->getFilterErrors());
    }
}