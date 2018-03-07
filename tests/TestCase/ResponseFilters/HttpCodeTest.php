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

use PHPUnit\Framework\TestCase;
use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ResponseFilters\HttpCode;

class HttpCodeTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('httpCode', (new HttpCode())->getName());
    }

    public function testValidateParams()
    {
        $filter = new HttpCode();

        $this->assertFalse($filter->validateParams([['sample' => 'array']]));
        $this->assertTrue($filter->validateParams([200]));
    }

    public function testValidStatusCode()
    {
        $filter = new HttpCode();
        $apiResponse = new ApiClientResponse(200, [], '');

        $filter->call($apiResponse, [200]);

        $statsCollector = $filter->getStatsCollector();
        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertFalse($statsCollector->hasErrors());
    }

    public function testInvalidStatusCode()
    {
        $filter = new HttpCode();
        $apiResponse = new ApiClientResponse(404, [], '');

        $filter->call($apiResponse, [200]);

        $statsCollector = $filter->getStatsCollector();
        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertTrue($statsCollector->hasErrors());

        $this->assertSame($statsCollector->getFilterErrors(), [
            [
                'filter'        => $filter,
                'errorCode'     => HttpCode::INVALID_STATUS_CODE,
                'givenValue'    => 404,
                'expectedValue' => 200
            ]
        ]);
    }
}