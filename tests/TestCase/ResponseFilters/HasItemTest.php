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
use RestControl\TestCase\ResponseFilters\HasItemFilter;
use RestControl\TestCase\StatsCollector\EndContextException;
use RestControl\Utils\AbstractResponseItem;

class HasItemTest extends TestCase
{
    public function testName()
    {
        $this->assertSame(
            'hasItem',
            (new HasItemFilter())->getName()
        );
    }

    public function testValidateParams()
    {
        $filter = new HasItemFilter();
        $item = $this->getMockBuilder(AbstractResponseItem::class)
            ->getMockForAbstractClass();

        $this->assertTrue($filter->validateParams([
            $item,
        ]));

        $this->assertTrue($filter->validateParams([
            $item,
            'samplePath',
        ]));

        $this->assertTrue($filter->validateParams([
            $item,
            null,
            true,
        ]));

        $this->assertFalse($filter->validateParams([
            $item,
            true,
        ]));

        $this->assertFalse($filter->validateParams([
            new \stdClass(),
        ]));

        $this->assertFalse($filter->validateParams([
            $item,
            new \stdClass(),
        ]));
    }

    public function testInvalidResponseBody()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem();

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '12312331'
        );

        try{
            $filter->call($apiClientResponse, [$item]);
        } catch(EndContextException $e) {
            $statsCollector = $filter->getStatsCollector();

            $this->assertTrue($statsCollector->hasErrors());
            $this->assertSame(1, $statsCollector->getAssertionsCount());
            $this->assertSame([
                [
                    'filter'        => $filter,
                    'errorCode'     => HasItemFilter::ERROR_INVALID_BODY,
                    'givenValue'    => '12312331',
                    'expectedValue' => 'array|object|json',
                ],
            ], $statsCollector->getFilterErrors());
        }
    }

    public function testValidation()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem();

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"id":"46fcc8c3-53d6-447c-9a7a-58d035e6b18d"}'
        );

        $filter->call($apiClientResponse, [$item]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertFalse($statsCollector->hasErrors());
        $this->assertSame(7, $statsCollector->getAssertionsCount());
    }

    public function testValidationWithJsonPath()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem();

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"sampleUser":{"id":"46fcc8c3-53d6-447c-9a7a-58d035e6b18d"}}'
        );

        $filter->call($apiClientResponse, [$item, 'sampleUser']);

        $statsCollector = $filter->getStatsCollector();

        $this->assertFalse($statsCollector->hasErrors());
        $this->assertSame(7, $statsCollector->getAssertionsCount());
    }

    public function testInvalidValueForValidation()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem();

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"id": "invalidUuid"}'
        );

        $filter->call($apiClientResponse, [$item]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(7, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => HasItemFilter::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                'givenValue'    => 'invalidUuid',
                'expectedValue' => [
                    'path'      => '$.id',
                    'validator' => 'uuid',
                    'config'    => [],
                ],
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testInvalidStructure()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem();

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"name": "shouldBeArrayHere"}'
        );

        $filter->call($apiClientResponse, [$item]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(4, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => HasItemFilter::ERROR_INVALID_RESPONSE_VALUE_STRUCTURE,
                'givenValue'    => 'shouldBeArrayHere',
                'expectedValue' => [
                    'path'       => '$.name',
                    'validators' => [
                        'array' => [],
                    ]
                ],
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testRequiredInvalidValues()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem([
            'id' => '3e07d927-d2fa-4e73-8033-c09b0645eb8a',
        ]);

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"id":"46fcc8c3-53d6-447c-9a7a-58d035e6b18d"}'
        );

        $filter->call($apiClientResponse, [$item]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(8, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => HasItemFilter::ERROR_INVALID_RESPONSE_REQUIRED_VALUES,
                'givenValue'    => [
                    'id' => '46fcc8c3-53d6-447c-9a7a-58d035e6b18d',
                ],
                'expectedValue' => [
                    'id' => '3e07d927-d2fa-4e73-8033-c09b0645eb8a',
                ],
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testRequiredValuesWithExpression()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem([
            'id' => new Expression('endsWith', ['58d035e6b18d']),
        ]);

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"id":"46fcc8c3-53d6-447c-9a7a-58d035e6b18d"}'
        );

        $filter->call($apiClientResponse, [$item]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertFalse($statsCollector->hasErrors());
        $this->assertSame(8, $statsCollector->getAssertionsCount());
    }

    public function testRequiredInvalidValuesWithExpression()
    {
        $expression = new Expression('endsWith', ['zxczxasdaddd']);
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem([
            'id' => $expression,
        ]);

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"id":"46fcc8c3-53d6-447c-9a7a-58d035e6b18d"}'
        );

        $filter->call($apiClientResponse, [$item]);

        $statsCollector = $filter->getStatsCollector();

        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame(8, $statsCollector->getAssertionsCount());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => HasItemFilter::ERROR_INVALID_RESPONSE_REQUIRED_VALUES,
                'givenValue'    => [
                    'id' => '46fcc8c3-53d6-447c-9a7a-58d035e6b18d',
                ],
                'expectedValue' => [
                    'id' => $expression
                ],
            ],
        ], $statsCollector->getFilterErrors());
    }

    public function testsRepeatArraySegmentValue()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItemAnother();

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"name":[{"firstName":"sample","lastName":"name"}, {"firstName":"anotherSample","lastName":"name"}]}'
        );

        $filter->call($apiClientResponse, [$item, 'sampleUser']);

        $statsCollector = $filter->getStatsCollector();
        $this->assertFalse($statsCollector->hasErrors());
    }

    public function testsRepeatValueSimpleValue()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItemAnother();

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"sampleRepeatedValue": ["46fcc8c3-53d6-447c-9a7a-58d035e6b18d", "asdd"]}'
        );

        $filter->call($apiClientResponse, [$item]);

        $statsCollector = $filter->getStatsCollector();
        $this->assertTrue($statsCollector->hasErrors());
        $this->assertSame([
            [
                'filter'        => $filter,
                'errorCode'     => HasItemFilter::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                'givenValue'    => 'asdd',
                'expectedValue' => [
                    'path'      => '$.sampleRepeatedValue.1',
                    'validator' => 'uuid',
                    'config'    => [],
                ],
            ],
        ], $statsCollector->getFilterErrors());
    }
}