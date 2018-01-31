<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\ApiClient;

/**
 * Class ApiClientResponse
 * @package RestControl\ApiClient
 */
class ApiClientResponse
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var mixed
     */
    protected $body;

    public function __construct(
        $statusCode,
        array $headers = [],
        $body
    ){
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeader($headerName, $default = null)
    {
        if(!isset($this->headers[$headerName])) {
            return $default;
        }

        return $this->headers[$headerName];
    }

    /**
     * @return array
     */
    public function getContentType()
    {
        return (array) (isset($this->headers['Content-Type']) ? $this->headers['Content-Type'] : '');
    }

}