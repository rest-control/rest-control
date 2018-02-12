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
use RestControl\TestCase\ResponseFilters\FilterException;
use RestControl\TestCase\ResponseFilters\HasItemFilter;
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
        } catch(FilterException $e) {
            $this->assertSame(HasItemFilter::ERROR_INVALID_BODY, $e->getErrorType());
        }
    }

    public function testValidation()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem();

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"id": "46fcc8c3-53d6-447c-9a7a-58d035e6b18d"}'
        );

        $this->assertNull(
            $filter->call($apiClientResponse, [$item])
        );
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

        try{
            $filter->call($apiClientResponse, [$item]);
        } catch (FilterException $e) {
            $this->assertSame(
                HasItemFilter::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                $e->getErrorType()
            );

            $this->assertSame(
                'invalidUuid',
                $e->getGiven()
            );
        }
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

        try{
            $filter->call($apiClientResponse, [$item]);
        } catch (FilterException $e) {

            $this->assertSame(
                HasItemFilter::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                $e->getErrorType()
            );

            $this->assertSame(
                [
                    'path' => '[name][firstName]',
                    'validators' => [
                        'optional' => [],
                    ],
                ],
                $e->getExpected()
            );
        }
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

        try{
            $filter->call($apiClientResponse, [$item]);
        } catch (FilterException $e) {

            $this->assertSame(
                HasItemFilter::ERROR_INVALID_RESPONSE_REQUIRED_VALUES,
                $e->getErrorType()
            );
        }
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

        $this->assertNull($filter->call($apiClientResponse, [$item]));
    }

    public function testRequiredInvalidValuesWithExpression()
    {
        $filter = new HasItemFilter();
        $item   = new SampleResponseItem([
            'id' => new Expression('endsWith', ['zxczxasdaddd']),
        ]);

        $apiClientResponse = new ApiClientResponse(
            200,
            [],
            '{"id":"46fcc8c3-53d6-447c-9a7a-58d035e6b18d"}'
        );

        try{
            $filter->call($apiClientResponse, [$item]);
        } catch(FilterException $e) {

            $this->assertSame(
                HasItemFilter::ERROR_INVALID_RESPONSE_REQUIRED_VALUES,
                $e->getErrorType()
            );
        }
    }
}