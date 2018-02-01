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
use RestControl\TestCase\ResponseFilters\FilterException;
use RestControl\TestCase\ResponseFilters\JsonFilter;
use PHPUnit\Framework\TestCase;

class JsonFilterTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('json', (new JsonFilter())->getName());
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
            ''
        );

        $this->assertNull($filter->call($apiClientResponse, [true, true]));
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
            ''
        );

        try{
            $filter->call($apiClientResponse);
        } catch(FilterException $e){
            $this->assertSame($e->getFilter(), $filter);
            $this->assertSame($e->getErrorType(), JsonFilter::ERROR_WRONG_CONTENT_TYPE);
            $this->assertSame($e->getExpected(), '/application/json/');
            $this->assertSame($e->getGiven(), [
                'another content type',
                'application/wrongjson;charset=utf-8',
            ]);
        }
    }

    public function testInvalidBody()
    {

        $filter = new JsonFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            ''
        );

        try{
            $filter->call($apiClientResponse, [false]);
        } catch(FilterException $e){
            $this->assertSame($e->getFilter(), $filter);
            $this->assertSame($e->getErrorType(), JsonFilter::ERROR_INVALID_BODY);
            $this->assertSame($e->getExpected(), null);
            $this->assertSame($e->getGiven(), '');
        }
    }

    public function testIgnoreContentTypeAndBody()
    {

        $filter = new JsonFilter();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            ''
        );

        $this->assertNull($this->assertNull($filter->call($apiClientResponse, [false, true])));
    }
}