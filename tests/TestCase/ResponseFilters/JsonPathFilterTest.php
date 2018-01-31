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

use Api\ApiClient\ApiClientResponse;
use Api\TestCase\ResponseFilters\FilterException;
use Api\TestCase\ResponseFilters\JsonPathFilter;
use PHPUnit\Framework\TestCase;

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
        $this->assertTrue($filter->validateParams(['sample', '==', 123]));
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
        } catch(FilterException $e){
            $this->assertSame($e->getFilter(), $filter);
            $this->assertSame($e->getErrorType(), JsonPathFilter::ERROR_WRONG_BODY_FORMAT);
            $this->assertSame($e->getExpected(), 'array|object|json_string');
            $this->assertSame($e->getGiven(), 'invalid body format');
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

        try{
            $filter->call($apiClientResponse, [
                'test.sample',
                '===',
                986785
            ]);
        } catch(FilterException $e){
            $this->assertSame($e->getFilter(), $filter);
            $this->assertSame($e->getErrorType(), JsonPathFilter::ERROR_INVALID_VALUE);
            $this->assertSame($e->getExpected(), '$value === 986785');
            $this->assertSame($e->getGiven(), 1234);
        }
    }
}