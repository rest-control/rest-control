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
 * Class RequestSchema
 * @package RestControl\Utils
 */
class ApiClientRequest
{
    /**
     * HTTP method name.
     *
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $urlParams = [];

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $formParams = [];

    /**
     * @var null|mixed
     */
    protected $body = null;

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = (string) $method;
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @param array $formParams
     *
     * @return $this
     */
    public function form(array $formParams = [])
    {
        $this->formParams = $formParams;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $url
     * @param array $urlParameters
     *
     * @return $this
     */
    public function setUrl($url, array $urlParameters = [])
    {
        $this->urlParams = (array) $urlParameters;
        $this->url       = (string) $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getUrlParams()
    {
        return (array) $this->urlParams;
    }

    /**
     * @return array
     */
    public function getFormParams()
    {
        return (array) $this->formParams;
    }

    /**
     * @return mixed|null
     */
    public function getBody()
    {
        return $this->body;
    }
}