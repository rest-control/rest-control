<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase;

use RestControl\TestCase\Traits\RequestMethodsTrait;
use RestControl\TestCase\Traits\RequestTrait;

class Request extends AbstractChain
{
    use RequestTrait,
        RequestMethodsTrait;

    const METHOD_POST = 'post';
    const METHOD_GET  = 'get';
    const METHOD_PUT  = 'put';

    const CO_METHOD      = 'method';
    const CO_BODY        = 'body';
    const CO_FORM_PARAMS = 'form_params';

    /**
     * @var null|Response
     */
    protected $expectedResponse = null;

    /**
     * Request constructor.
     *
     * @param Response|null $expectedResponse
     */
    public function __construct(Response $expectedResponse = null)
    {
        if(!$expectedResponse) {
            return;
        }

        $this->expectedResponse = $expectedResponse;
    }

    /**
     * @return Response
     */
    public function expectedResponse()
    {
        if($this->expectedResponse) {
            return $this->expectedResponse;
        }

        $response = new Response($this);
        $this->expectedResponse = $response;

        return $response;
    }
}