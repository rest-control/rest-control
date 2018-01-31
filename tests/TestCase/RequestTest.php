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

use Api\TestCase\ChainObject;
use Api\TestCase\Request;
use Api\TestCase\Response;
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

    public function testForm()
    {
        $request = new Request();
        $request->form([
            'id' => 23,
            'sample' => 'asdfasdf',
        ]);

        $this->assertSame(1, $request->_getChainLength());

        $obj = $request->_getChainObject(Request::CO_FORM);
        $this->assertInstanceOf(ChainObject::class, $obj);
        $this->assertSame(Request::CO_FORM, $obj->getObjectName());

        $this->assertSame([
            'id' => 23,
            'sample' => 'asdfasdf',
        ], $obj->getParam(0));
    }

    public function testExpectedResponse()
    {
        $request = new Request();
        $this->assertInstanceOf(Response::class, $request->expectedResponse());
        $this->assertSame(1, $request->_getChainLength());
        $this->assertInstanceOf(Response::class, $request->expectedResponse());
        $this->assertSame(1, $request->_getChainLength());
    }
}