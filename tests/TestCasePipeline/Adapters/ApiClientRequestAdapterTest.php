<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCasePipeline\Adapters;

use RestControl\ApiClient\ApiClientRequest;
use RestControl\TestCase\Request;
use RestControl\TestCasePipeline\Adapters\ApiClientRequestAdapter;
use PHPUnit\Framework\TestCase;

class ApiClientRequestAdapterTest extends TestCase
{
    public function testHttpMethod()
    {
        $request = new Request();
        $request->post('http://sample.org/asdasd/{id}', [
            'id' => 12345,
            'sample' => 4566,
        ]);

        $adapter = new ApiClientRequestAdapter();
        $apiRequest = $adapter->transform($request);

        $this->assertInstanceOf(ApiClientRequest::class, $apiRequest);

        $this->assertSame('http://sample.org/asdasd/{id}', $apiRequest->getUrl());
        $this->assertSame(Request::METHOD_POST, $apiRequest->getMethod());
        $this->assertSame([
            'id' => 12345,
            'sample' => 4566,
        ], $apiRequest->getUrlParams());

        $request->get('http://sample.org/another/asdfg/{etc}', [
            'etc' => 546,
            'zxc' => 577,
        ]);

        $apiRequest = $adapter->transform($request);

        $this->assertSame('http://sample.org/another/asdfg/{etc}', $apiRequest->getUrl());
        $this->assertSame(Request::METHOD_GET, $apiRequest->getMethod());
        $this->assertSame([
            'etc' => 546,
            'zxc' => 577,
        ], $apiRequest->getUrlParams());

    }

    public function testFormParams()
    {
        $request = new Request();
        $request->formParams([
            'name' => 'foo',
            'password' => 'bar',
        ]);

        $adapter = new ApiClientRequestAdapter();
        $apiRequest = $adapter->transform($request);

        $this->assertSame([
            'name' => 'foo',
            'password' => 'bar',
        ], $apiRequest->getFormParams());
    }

    public function testBody()
    {
        $request = new Request();
        $request->body('Sample body test');

        $adapter = new ApiClientRequestAdapter();
        $apiRequest = $adapter->transform($request);

        $this->assertSame('Sample body test', $apiRequest->getBody());
    }

    public function testArrayBody()
    {
        $request = new Request();
        $request->body([
            'sample' => 'body',
        ]);

        $adapter = new ApiClientRequestAdapter();
        $apiRequest = $adapter->transform($request);

        $this->assertSame([
            'sample' => 'body',
        ], $apiRequest->getBody());
    }

    public function testDoubleBody()
    {
        $request = new Request();
        $request->body('Sample body')
                ->body('Another body');

        $adapter = new ApiClientRequestAdapter();
        $apiRequest = $adapter->transform($request);

        $this->assertSame('Another body', $apiRequest->getBody());
    }

    public function testHeaders()
    {
        $request = new Request();
        $request->header('sample', 'header');
        $request->header('sample2', 'header2');

        $adapter = new ApiClientRequestAdapter();
        $apiRequest = $adapter->transform($request);

        $this->assertSame([
            'sample'  => 'header',
            'sample2' => 'header2',
        ], $apiRequest->getHeaders());
    }
}