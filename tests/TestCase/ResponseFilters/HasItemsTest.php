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
use RestControl\TestCase\ResponseFilters\HasItemsFilter;
use RestControl\Utils\ResponseItemsCollection;

class HasItemsTest extends TestCase
{
    public function testName()
    {
        $this->assertSame(
            'hasItems',
            (new HasItemsFilter())->getName()
        );
    }

    public function testValidateParams()
    {
        $filter = new HasItemsFilter();
        $collection = new ResponseItemsCollection(SampleResponseItem::class);

        $this->assertFalse($filter->validateParams([new \stdClass]));
        $this->assertFalse($filter->validateParams([$collection, new \stdClass()]));
        $this->assertTrue($filter->validateParams([$collection]));
        $this->assertTrue($filter->validateParams([$collection, '$.sample.path']));
    }

    public function testsInvalidValueInCollection()
    {
        $filter = new HasItemsFilter();
        $collection = new ResponseItemsCollection(SampleResponseItem::class);

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '[{"id":"46fcc8c3-53d6-447c-9a7a-58d035e6b18d"},{"id":"sample"}]',
            123
        );

        $filter->call($apiClientResponse, [$collection]);
        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame([
            [
                'filter' => $filter,
                'errorCode' => HasItemsFilter::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                'givenValue' => 'sample',
                'expectedValue' => [
                    'path'      => '$.1.id',
                    'validator' => 'uuid',
                    'config'    => [],
                ],
            ]
        ], $statsCollector->getFilterErrors());
    }

    public function testsInvalidValueInRequiredItemValues()
    {
        $filter = new HasItemsFilter();
        $collection = new ResponseItemsCollection(SampleResponseItem::class);
        $collection->addItem(
            new SampleResponseItem([
                'id' => '46fcc8c3-53d6-447c-9a7a-58d035e6b18d',
            ])
        );
        $collection->addItem(
            new SampleResponseItem([
                'id' => '25159e1d-ac7a-4b70-a13d-b919b8c05868',
            ])
        );

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '[{"id":"46fcc8c3-53d6-447c-9a7a-58d035e6b18d"},{"id":"0948e203-be32-4de9-ab49-242b0e3394a9"}]',
            123
        );

        $filter->call($apiClientResponse, [$collection]);
        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame([
            [
                'filter' => $filter,
                'errorCode' => HasItemsFilter::ERROR_INVALID_RESPONSE_REQUIRED_VALUES,
                'givenValue' => [
                    'id' => '0948e203-be32-4de9-ab49-242b0e3394a9',
                ],
                'expectedValue' => [
                    'id' => '25159e1d-ac7a-4b70-a13d-b919b8c05868',
                ],
            ]
        ], $statsCollector->getFilterErrors());
    }
}