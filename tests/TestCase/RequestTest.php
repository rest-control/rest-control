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

use RestControl\TestCase\ChainObject;
use RestControl\TestCase\Request;
use RestControl\TestCase\Response;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testPost()
    {
        $this->checkRequest(
            2,
            Request::METHOD_POST,
            'http://sample/{id}',
            [
                'id' => 23,
            'sample' => 'asdfasdf',
            ],
            'sample body'
        );
    }

    public function testGet()
    {
        $this->checkRequest(
            1,
            Request::METHOD_GET,
            'http://sample/{id}',
            [
                'id' => 23,
                'sample' => 'asdfasdf',
            ]
        );
    }

    public function testPut()
    {
        $this->checkRequest(
            2,
            Request::METHOD_PUT,
            'http://sample/{id}',
            [
                'id' => 23,
                'sample' => 'asdfasdf',
            ],
            'sample body'
        );
    }

    public function testBody()
    {
        $request = new Request();
        $request->body('Sample body');

        $this->assertSame(1, $request->_getChainLength());

        $obj = $request->_getChainObject(Request::CO_BODY);
        $this->assertInstanceOf(ChainObject::class, $obj);
        $this->assertSame(Request::CO_BODY, $obj->getObjectName());

        $this->assertSame('Sample body', $obj->getParam(0));
    }

    public function testFormParams()
    {
        $request = new Request();
        $request->formParams([
            'id' => 23,
            'sample' => 'asdfasdf',
        ]);

        $this->assertSame(1, $request->_getChainLength());

        $obj = $request->_getChainObject(Request::CO_FORM_PARAMS);
        $this->assertInstanceOf(ChainObject::class, $obj);
        $this->assertSame(Request::CO_FORM_PARAMS, $obj->getObjectName());

        $this->assertSame([
            'id' => 23,
            'sample' => 'asdfasdf',
        ], $obj->getParam(0));
    }

    public function testExpectedResponse()
    {
        $request = new Request();
        $this->assertInstanceOf(Response::class, $request->expectedResponse());
        $this->assertSame(0, $request->_getChainLength());
        $this->assertInstanceOf(Response::class, $request->expectedResponse());
        $this->assertSame(0, $request->_getChainLength());
    }

    public function testDelete()
    {
        $this->checkRequest(
            2,
            Request::METHOD_DELETE,
            'http://sample/{id}',
            [
                'id' => 23,
                'sample' => 'asdfasdf',
            ],
            'sample body'
        );
    }

    public function testHead()
    {
        $this->checkRequest(
            1,
            Request::METHOD_HEAD,
            'http://sample/{id}',
            [
                'id' => 23,
                'sample' => 'asdfasdf',
            ]
        );
    }

    public function testPatch()
    {
        $this->checkRequest(
            2,
            Request::METHOD_PATCH,
            'http://sample/{id}',
            [
                'id' => 23,
                'sample' => 'asdfasdf',
            ],
            'sample body'
        );
    }

    public function testPurge()
    {
        $this->checkRequest(
            1,
            Request::METHOD_PURGE,
            'http://sample/{id}',
            [
                'id' => 23,
                'sample' => 'asdfasdf',
            ]
        );
    }


    public function testOptions()
    {
        $this->checkRequest(
            2,
            Request::METHOD_OPTIONS,
            'http://sample/{id}',
            [
                'id' => 23,
                'sample' => 'asdfasdf',
            ],
            'sample body'
        );
    }

    protected function checkRequest(
        $expectedChainLength = 1,
        $method,
        $uri,
        array $urlParams = [],
        $body = null
    ){
        $request = new Request();
        $request->{$method}($uri, $urlParams, $body);

        $this->assertSame($expectedChainLength, $request->_getChainLength());

        $obj = $request->_getChainObject(Request::CO_METHOD);
        $this->assertInstanceOf(ChainObject::class, $obj);
        $this->assertSame(Request::CO_METHOD, $obj->getObjectName());

        $this->assertSame($method, $obj->getParam(0));
        $this->assertSame($uri, $obj->getParam(1));
        $this->assertSame($urlParams, $obj->getParam(2));

        if(!$body) {
            return;
        }

        $bodyObj = $request->_getChainObject(Request::CO_BODY);
        $this->assertInstanceOf(ChainObject::class, $bodyObj);
        $this->assertSame(Request::CO_BODY, $bodyObj->getObjectName());

        $this->assertSame($body, $bodyObj->getParam(0));
    }
}