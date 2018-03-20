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
use RestControl\TestCase\ResponseFilters\CallFilter;
use PHPUnit\Framework\TestCase;

class CallFilterTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('call', (new CallFilter())->getName());
    }

    public function testValidateParams()
    {
        $filter = new CallFilter();

        $this->assertFalse($filter->validateParams(['sample']));
        $this->assertTrue($filter->validateParams([
            function(){return true;}
        ]));
    }

    public function testCall()
    {
        $filter = new CallFilter();
        $apiResponse = $this->getMockBuilder(ApiClientResponse::class)
                            ->disableOriginalConstructor()
                            ->getMock();
        $controlVar = 1;

        $filter->call($apiResponse, [function(ApiClientResponse $response) use($apiResponse, &$controlVar){

            $this->assertSame($apiResponse, $response);
            $controlVar = 100;
        }]);

        $this->assertSame(100, $controlVar);
    }
}