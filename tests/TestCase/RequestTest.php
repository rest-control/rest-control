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
        $request = new Request();
        $request->post('http://sample/{id}', [
            'id' => 23,
            'sample' => 'asdfasdf',
        ], 'sample body');

        $this->assertSame(2, $request->_getChainLength());

        $obj = $request->_getChainObject(Request::CO_METHOD);
        $this->assertInstanceOf(ChainObject::class, $obj);
        $this->assertSame(Request::CO_METHOD, $obj->getObjectName());

        $this->assertSame(Request::METHOD_POST, $obj->getParam(0));
        $this->assertSame('http://sample/{id}', $obj->getParam(1));
        $this->assertSame([
            'id' => 23,
            'sample' => 'asdfasdf',
        ], $obj->getParam(2));

        $bodyObj = $request->_getChainObject(Request::CO_BODY);
        $this->assertInstanceOf(ChainObject::class, $bodyObj);
        $this->assertSame(Request::CO_BODY, $bodyObj->getObjectName());

        $this->assertSame('sample body', $bodyObj->getParam(0));
    }

    public function testGet()
    {
        $request = new Request();
        $request->get('http://sample/{id}', [
            'id' => 23,
            'sample' => 'asdfasdf',
        ]);

        $this->assertSame(1, $request->_getChainLength());

        $obj = $request->_getChainObject(Request::CO_METHOD);
        $this->assertInstanceOf(ChainObject::class, $obj);
        $this->assertSame(Request::CO_METHOD, $obj->getObjectName());

        $this->assertSame(Request::METHOD_GET, $obj->getParam(0));
        $this->assertSame('http://sample/{id}', $obj->getParam(1));
        $this->assertSame([
            'id' => 23,
            'sample' => 'asdfasdf',
        ], $obj->getParam(2));
    }

    public function testPut()
    {
        $request = new Request();
        $request->put('http://sample/{id}', [
            'id' => 23,
            'sample' => 'asdfasdf',
        ], 'sample body');

        $this->assertSame(2, $request->_getChainLength());

        $obj = $request->_getChainObject(Request::CO_METHOD);
        $this->assertInstanceOf(ChainObject::class, $obj);
        $this->assertSame(Request::CO_METHOD, $obj->getObjectName());

        $this->assertSame(Request::METHOD_PUT, $obj->getParam(0));
        $this->assertSame('http://sample/{id}', $obj->getParam(1));
        $this->assertSame([
            'id' => 23,
            'sample' => 'asdfasdf',
        ], $obj->getParam(2));

        $bodyObj = $request->_getChainObject(Request::CO_BODY);
        $this->assertInstanceOf(ChainObject::class, $bodyObj);
        $this->assertSame(Request::CO_BODY, $bodyObj->getObjectName());

        $this->assertSame('sample body', $bodyObj->getParam(0));
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
        $request = new Request();
        $request->delete('http://sample/{id}', [
            'id' => 23,
            'sample' => 'asdfasdf',
        ], 'sample body');

        $this->assertSame(2, $request->_getChainLength());

        $obj = $request->_getChainObject(Request::CO_METHOD);
        $this->assertInstanceOf(ChainObject::class, $obj);
        $this->assertSame(Request::CO_METHOD, $obj->getObjectName());

        $this->assertSame(Request::METHOD_DELETE, $obj->getParam(0));
        $this->assertSame('http://sample/{id}', $obj->getParam(1));
        $this->assertSame([
            'id' => 23,
            'sample' => 'asdfasdf',
        ], $obj->getParam(2));

        $bodyObj = $request->_getChainObject(Request::CO_BODY);
        $this->assertInstanceOf(ChainObject::class, $bodyObj);
        $this->assertSame(Request::CO_BODY, $bodyObj->getObjectName());

        $this->assertSame('sample body', $bodyObj->getParam(0));
    }
}