<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\Traits;

use RestControl\TestCase\Request;

trait RequestMethodsTrait
{
    /**
     * @param string     $url
     * @param array      $urlParams
     * @param null|mixed $body
     *
     * @return $this
     */
    public function post($url, array $urlParams = [], $body = null)
    {
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_POST, $url, $urlParams])
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
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_GET, $url, $urlParams]);
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
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_PUT, $url, $urlParams])
                    ->body($body);
    }

    /**
     * @param string     $url
     * @param array      $urlParams
     * @param null|mixed $body
     *
     * @return $this
     */
    public function patch($url, array $urlParams = [], $body = null)
    {
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_PATCH, $url, $urlParams])
                    ->body($body);
    }

    /**
     * @param string     $url
     * @param array      $urlParams
     * @param null|mixed $body
     *
     * @return $this
     */
    public function delete($url, array $urlParams = [], $body = null)
    {
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_DELETE, $url, $urlParams])
                    ->body($body);
    }

    /**
     * @param string $url
     * @param array  $urlParams
     *
     * @return $this
     */
    public function head($url, array $urlParams = [])
    {
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_HEAD, $url, $urlParams]);
    }

    /**
     * @param string $url
     * @param array  $urlParams
     *
     * @return $this
     */
    public function purge($url, array $urlParams = [])
    {
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_PURGE, $url, $urlParams]);
    }

    /**
     * @param string     $url
     * @param array      $urlParams
     * @param null|mixed $body
     *
     * @return $this
     */
    public function options($url, array $urlParams = [], $body = null)
    {
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_OPTIONS, $url, $urlParams])
                    ->body($body);
    }

    /**
     * @param string $url
     * @param array  $urlParams
     *
     * @return $this
     */
    public function trace($url, array $urlParams = [])
    {
        return $this->remove(Request::CO_METHOD)
                    ->_add(Request::CO_METHOD, [Request::METHOD_TRACE, $url, $urlParams]);
    }

    /**
     * @param string $url
     * @param array  $urlParams
     *
     * @return $this
     */
    public function connect($url, array $urlParams = [])
    {
        return $this->remove(Request::CO_METHOD)
            ->_add(Request::CO_METHOD, [Request::METHOD_CONNECT, $url, $urlParams]);
    }
}