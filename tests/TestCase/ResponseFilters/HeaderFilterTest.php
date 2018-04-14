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
use RestControl\TestCase\ResponseFilters\HeaderFilter;
use PHPUnit\Framework\TestCase;

class HeaderFilterTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('header', (new HeaderFilter())->getName());
    }

    public function testValidateParams()
    {
        $filter = new HeaderFilter();

        $this->assertFalse($filter->validateParams(['sample']));
        $this->assertTrue($filter->validateParams([
            'Content-Type',
            new Expression('containsString', ['application/json'])
        ]));
    }

    public function testInvalidHeader()
    {
        $filter = new HeaderFilter();

        $apiClientResponse = new ApiClientResponse(
            200,
            [
                'Content-Type' => [
                    'application/json; charset=utf-8',
                ],
            ],
            '',
            0
        );

        $expression = new Expression('containsString', ['html']);

        $filter->call($apiClientResponse, [
            'Content-Type',
            $expression
        ]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(1, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => HeaderFilter::ERROR_INVALID_VALUE,
                'givenValue'    => [
                    'application/json; charset=utf-8'
                ],
                'expectedValue' => $expression,
            ],
        ], $statsCollector->getFilterErrors());
    }
}