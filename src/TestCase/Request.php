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

/**
 * Class Request
 *
 * @package RestControl\TestCase
 */
class Request extends AbstractChain
{
    const METHOD_POST = 'post';
    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';

    const CO_EXPECTED_RESPONSE = 'expectedResponse';
    const CO_METHOD = 'method';
    const CO_BODY = 'body';
    const CO_FORM = 'form';

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

        $this->_add(self::CO_EXPECTED_RESPONSE, [$expectedResponse]);
        $this->expectedResponse = $expectedResponse;
    }

    /**
     * @param string     $url
     * @param array      $urlParams
     * @param null|mixed $body
     *
     * @return $this
     */
    public function post($url, array $urlParams = [], $body = null)
    {
        return $this->remove(self::CO_METHOD)
                    ->_add(self::CO_METHOD, [self::METHOD_POST, $url, $urlParams])
                    ->body($body);
    }

    /**
     * @param string $url
     * @param array  $urlParams
     *
     * @return $this
     */
    public function get($url, array $urlParams = [])
    {
        return $this->remove(self::CO_METHOD)
                    ->_add(self::CO_METHOD, [self::METHOD_GET, $url, $urlParams]);
    }

    /**
     * @param string     $url
     * @param array      $urlParams
     * @param null|mixed $body
     *
     * @return $this
     */
    public function put($url, array $urlParams = [], $body = null)
    {
        return $this->remove(self::CO_METHOD)
                    ->_add(self::CO_METHOD, [self::METHOD_PUT, $url, $urlParams])
                    ->body($body);
    }

    /**
     * @param null|mixed $body
     *
     * @return $this
     */
    public function body($body = null)
    {
        return $this->_add(self::CO_BODY, func_get_args());
    }

    /**
     * @param array $formParams
     *
     * @return $this
     */
    public function form(array $formParams = [])
    {
        return $this->_add(self::CO_FORM, func_get_args());
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

        $this->_add(self::CO_EXPECTED_RESPONSE, [ $response]);
        $this->expectedResponse = $response;

        return $response;
    }
}