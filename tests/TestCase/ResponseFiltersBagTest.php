<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase;

use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;
use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ChainObject;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ResponseFilters\FilterException;
use RestControl\TestCase\ResponseFilters\FilterInterface;
use RestControl\TestCase\ResponseFilters\HeaderFilter;
use RestControl\TestCase\ResponseFilters\JsonFilter;
use RestControl\TestCase\ResponseFilters\JsonPathFilter;
use RestControl\TestCase\ResponseFiltersBag;

class ResponseFiltersBagTest extends TestCase
{
    public function testDefaultFilters()
    {
        $bag = new ResponseFiltersBag();

        $filters = $bag->getFilters();

        $this->assertInstanceOf(JsonFilter::class, $filters[0]);
        $this->assertInstanceOf(HeaderFilter::class, $filters[1]);
        $this->assertInstanceOf(JsonPathFilter::class, $filters[2]);
        $this->assertInstanceOf(HeaderFilter::class, $filters[3]);
    }

    public function testGetFilter()
    {
        $bag = new ResponseFiltersBag();
        $jsonFilter = new JsonFilter();

        $this->assertInstanceOf(
            JsonFilter::class,
            $bag->getFilter($jsonFilter->getName())
        );
    }

    public function testGetInvalidFilter()
    {
        $bag = new ResponseFiltersBag();

        $this->assertNull(
            $bag->getFilter('sample filter wrong ___ format !@#$%E^&')
        );
    }

    public function testAddInvalidFilter()
    {
        $bag = new ResponseFiltersBag();

        $this->expectException(InvalidArgumentException::class);

        $bag->addFilters([new \stdClass()]);
    }

    public function testFilterResponseFilterInternalException()
    {
        $exception = new \Exception('Sample exception');

        $filter = $this->getMockBuilder(FilterInterface::class)
            ->setMethods(['getName', 'validateParams'])
            ->getMockForAbstractClass();
        $filter->expects($this->any())
            ->method('getName')
            ->willReturn('sample');
        $filter->expects($this->once())
            ->method('validateParams')
            ->willThrowException($exception);

        $responseChain = new TestResponseChain();
        $responseChain->sampleFilter('sample');

        $apiClientResponse = $this->getMockBuilder(ApiClientResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bag = new ResponseFiltersBag([
            $filter,
        ]);

        $statsCollector = $bag->filterResponse(
            $apiClientResponse,
            $responseChain
        );

        $this->assertTrue($statsCollector->hasErrors());
    }

    public function testFilterResponseInvalidParams()
    {
        $filter = $this->getMockBuilder(FilterInterface::class)
            ->setMethods(['getName', 'validateParams'])
            ->getMockForAbstractClass();
        $filter->expects($this->any())
            ->method('getName')
            ->willReturn('sample');
        $filter->expects($this->once())
            ->method('validateParams')
            ->with([
                'sample' => 'param',
                'sample2' => 'param2',
            ])
            ->willReturn(false);

        $responseChain = new TestResponseChain();
        $responseChain->sampleFilter('testNotExistingFilter');
        $responseChain->sampleFilter('sample', [
            'sample' => 'param',
            'sample2' => 'param2',
        ]);

        $apiClientResponse = $this->getMockBuilder(ApiClientResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bag = new ResponseFiltersBag([
            $filter,
        ]);

        $statsCollector = $bag->filterResponse(
            $apiClientResponse,
            $responseChain
        );

        $this->assertTrue($statsCollector->hasErrors());

        $filterErrors = $statsCollector->getFilterErrors();

        $this->assertCount(1, $filterErrors);
        $this->assertArrayHasKey('errorCode', $filterErrors[0]);
        $this->assertArrayHasKey('givenValue', $filterErrors[0]);
        $this->assertArrayHasKey('expectedValue', $filterErrors[0]);

        $this->assertSame(FilterInterface::ERROR_INVALID_PARAMS, $filterErrors[0]['errorCode']);
        $this->assertSame([
            'sample'  => 'param',
            'sample2' => 'param2',
        ], $filterErrors[0]['givenValue']);
        $this->assertSame(null, $filterErrors[0]['expectedValue']);

        $errors = $statsCollector->getErrors();

        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('message', $errors[0]);
        $this->assertSame( 'Response filter does not exists.', $errors[0]['message']);
        $this->assertArrayHasKey('context', $errors[0]);
        $this->assertArrayHasKey('chainObject', $errors[0]['context']);
        $this->assertInstanceOf(ChainObject::class, $errors[0]['context']['chainObject']);
    }
}