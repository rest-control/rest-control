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
use RestControl\TestCase\ResponseFilters\ContentTypeFilter;
use RestControl\TestCase\ResponseFilters\HeaderFilter;
use PHPUnit\Framework\TestCase;

class ContentTypeTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('contentType', (new ContentTypeFilter())->getName());
    }

    public function testValidateParams()
    {
        $filter = new ContentTypeFilter();

        $this->assertFalse($filter->validateParams(['sample']));
        $this->assertTrue($filter->validateParams([
            new Expression('containsString', ['application/json'])
        ]));
    }

    public function testInvalidContentType()
    {
        $filter = new ContentTypeFilter();

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