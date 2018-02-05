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

        $this->assertCount(4, $filters);
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

    /**
     * @group a
     */
    public function testFilterResponseFilterException()
    {
        $filter = $this->getMockBuilder(FilterInterface::class)
            ->setMethods(['getName', 'validateParams'])
            ->getMockForAbstractClass();
        $filter->expects($this->any())
            ->method('getName')
            ->willReturn('sample');
        $filter->expects($this->once())
            ->method('validateParams')
            ->willThrowException(new FilterException($filter, 'sample type', 'given', 'expected'));

        $responseChain = new TestResponseChain();
        $responseChain->sampleFilter('sample');

        $apiClientResponse = $this->getMockBuilder(ApiClientResponse::class)
            ->disableOriginalConstructor()
            ->getMock();

        $bag = new ResponseFiltersBag([
            $filter,
        ]);

        $errors = $bag->filterResponse(
            $apiClientResponse,
            $responseChain
        );

        $this->assertCount(1, $errors);

        /** @var FilterException $error */
        $error = $errors[0];

        $this->assertInstanceOf(\Exception::class, $error);
        $this->assertSame($filter, $error->getFilter());
        $this->assertSame('sample type', $error->getErrorType());
        $this->assertSame('given', $error->getGiven());
        $this->assertSame('expected', $error->getExpected());
    }

    public function testFilterResponseInternalException()
    {
        $filter = $this->getMockBuilder(FilterInterface::class)
                       ->setMethods(['getName', 'validateParams'])
                       ->getMockForAbstractClass();
        $filter->expects($this->any())
               ->method('getName')
               ->willReturn('sample');
        $filter->expects($this->once())
               ->method('validateParams')
               ->willThrowException(new \Exception('test exception'));

        $responseChain = new TestResponseChain();
        $responseChain->sampleFilter('sample');

        $apiClientResponse = $this->getMockBuilder(ApiClientResponse::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $bag = new ResponseFiltersBag([
            $filter,
        ]);

        $errors = $bag->filterResponse(
            $apiClientResponse,
            $responseChain
        );

        $this->assertCount(1, $errors);

        /** @var FilterException $error */
        $error = $errors[0];

        $this->assertInstanceOf(\Exception::class, $error);
        $this->assertSame($filter, $error->getFilter());
        $this->assertSame(FilterInterface::ERROR_INTERNAL_EXCEPTION, $error->getErrorType());

        /** @var \Exception $given */
        $given = $error->getGiven();
        $this->assertInstanceOf(\Exception::class, $given);
        $this->assertSame('test exception', $given->getMessage());

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

        $errors = $bag->filterResponse(
            $apiClientResponse,
            $responseChain
        );

        $this->assertCount(1, $errors);

        $error = $errors[0];

        $this->assertInstanceOf(FilterException::class, $error);
        $this->assertSame($filter, $error->getFilter());
        $this->assertSame(FilterInterface::ERROR_INVALID_PARAMS, $error->getErrorType());
        $this->assertSame([
            'sample' => 'param',
            'sample2' => 'param2',
        ], $error->getGiven());
    }

    public function testFilterResponseValidParams()
    {
        $filter = $this->getMockBuilder(FilterInterface::class)
            ->setMethods(['getName', 'validateParams', 'call'])
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
            ->willReturn(true);
        $filter->expects($this->once())
               ->method('call');

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

        $errors = $bag->filterResponse(
            $apiClientResponse,
            $responseChain
        );

        $this->assertEmpty($errors);
    }
}