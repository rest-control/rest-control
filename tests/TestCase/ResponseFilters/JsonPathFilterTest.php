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
use RestControl\TestCase\ResponseFilters\FilterException;
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
            'invalid body format'
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

    public function testInvalidValue()
    {
        $filter = new JsonPathFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"test":{
                "sample": 1234
            }}'
        );

        $expression = new Expression('equalsTo', [986785]);

        $filter->call($apiClientResponse, [
            'test.sample',
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
}