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

    /**
     * @var int
     */
    protected $bodySize;

    /**
     * Response time in milliseconds.
     *
     * @var int
     */
    protected $responseTime = 0;

    public function __construct(
        $statusCode,
        array $headers = [],
        $body,
        $bodySize,
        $responseTime = 0
    ){
        $this->statusCode   = $statusCode;
        $this->headers      = $headers;
        $this->body         = $body;
        $this->bodySize     = $bodySize;
        $this->responseTime = $responseTime;
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

    public function getBodySize()
    {
        return $this->bodySize;
    }

    public function getResponseTime()
    {
        return $this->responseTime;
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