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
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ResponseFilters\HasCookie;

class HasCookieTest extends TestCase
{
    public function testName()
    {
        $this->assertSame(
            'hasCookie',
            (new HasCookie())->getName()
        );
    }

    public function testValidParams()
    {
        $filter = new HasCookie();

        $this->assertFalse($filter->validateParams([]));
        $this->assertFalse($filter->validateParams([['invalid array']]));
        $this->assertFalse($filter->validateParams(['sample', ['invalid array']]));
        $this->assertFalse($filter->validateParams(['sample', 'sample', ['invalid array']]));
        $this->assertFalse($filter->validateParams([
            'sample',
            'sample',
            'sample',
            ['invalid array']
        ]));
        $this->assertFalse($filter->validateParams([
            'sample',
            'sample',
            'sample',
            'sample',
            ['invalid array']
        ]));
        $this->assertFalse($filter->validateParams([
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            ['invalid array']
        ]));
        $this->assertFalse($filter->validateParams([
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            ['invalid array']
        ]));
        $this->assertFalse($filter->validateParams([
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            ['invalid array']
        ]));
        $this->assertFalse($filter->validateParams([
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            ['invalid array']
        ]));
        $this->assertTrue($filter->validateParams([
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
            'sample',
        ]));
        $this->assertTrue($filter->validateParams([
            new Expression('sample', ['expression']),
            new Expression('sample', ['expression']),
            new Expression('sample', ['expression']),
            new Expression('sample', ['expression']),
            new Expression('sample', ['expression']),
            new Expression('sample', ['expression']),
            new Expression('sample', ['expression']),
            new Expression('sample', ['expression']),
            new Expression('sample', ['expression']),
        ]));
    }

    public function testCannotFindCookie()
    {
        $filter = new HasCookie();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            'body',
            123,
            0,
            [
                [
                    'Name' => 'SampleCookie',
                ],
            ]
        );

        $filter->call($apiClientResponse, ['Cookie']);

        $statsCollector = $filter->getStatsCollector();

        $this->assertCount(1, $statsCollector->getFilterErrors());

        $this->assertSame([
            [
                'filter' => $filter,
                'errorCode' => HasCookie::ERROR_CANNOT_FIND_COOKIE,
                'givenValue' => null,
                'expectedValue' => 'Cookie',
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testInvalidCookieValue()
    {
        $filter = new HasCookie();
        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            'body',
            123,
            0,
            [
                [
                    'Name'  => 'SampleCookie',
                    'Value' => 'SampleValue',
                ],
            ]
        );

        $filter->call($apiClientResponse, ['SampleCookie', 'Value']);

        $statsCollector = $filter->getStatsCollector();

        $this->assertCount(1, $statsCollector->getFilterErrors());

        $this->assertSame([
            [
                'filter' => $filter,
                'errorCode' => HasCookie::ERROR_INVALID_COOKIE_PARAM_VALUE,
                'givenValue' => 'SampleValue',
                'expectedValue' => 'Value',
            ],
        ], $statsCollector->getFilterErrors());
    }
}
