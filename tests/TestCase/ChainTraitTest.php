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
use RestControl\TestCase\ChainTrait;
use RestControl\TestCase\Request;
use RestControl\TestCase\Response;

class ChainTraitTest extends TestCase
{
    use ChainTrait;

    public function testGetRequestChain()
    {
        $request = new Request();
        $request->expectedResponse();

        $this->assertInstanceOf(
            Request::class,
            $this->__getRequestChain($request)
        );

        $this->assertInstanceOf(
            Request::class,
            $this->__getRequestChain($request->expectedResponse())
        );

        $this->expectException(InvalidArgumentException::class);
        $this->__getRequestChain(new UnsupportedChain());
    }
    
    public function testGetResponseChain()
    {
        $response = new Response();
        $response->expectedRequest();

        $this->assertInstanceOf(
            Response::class,
            $this->__getResponseChain($response)
        );

        $this->assertInstanceOf(
            Response::class,
            $this->__getResponseChain($response->expectedRequest())
        );

        $this->expectException(InvalidArgumentException::class);
        $this->__getResponseChain(new UnsupportedChain());
    }
}