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

use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ResponseFilters\CallFilter;
use RestControl\TestCase\ResponseFilters\HasCookie;
use RestControl\TestCase\ResponseFilters\HasItemFilter;
use RestControl\TestCase\ResponseFilters\HasItemsFilter;
use RestControl\TestCase\ResponseFilters\HeaderFilter;
use RestControl\TestCase\ResponseFilters\JsonFilter;
use RestControl\TestCase\ResponseFilters\JsonPathFilter;
use RestControl\TestCase\ResponseFilters\ResponseTimeFilter;
use RestControl\TestCase\ResponseFilters\SizeFilter;
use RestControl\TestCase\Traits\ResponseContentTypeTrait;
use RestControl\TestCase\Traits\ResponseHttpCodesTrait;
use RestControl\Utils\AbstractResponseItem;
use RestControl\Utils\ResponseItemsCollection;

class Response extends AbstractChain
{
    use ResponseHttpCodesTrait, ResponseContentTypeTrait;

    /**
     * @var null|Request
     */
    protected $expectedRequest = null;

    /**
     * Response constructor.
     *
     * @param Request|null $expectedRequest
     */
    public function __construct(Request $expectedRequest = null)
    {
        if(!$expectedRequest) {
            return;
        }

        $this->expectedRequest = $expectedRequest;
    }

    /**
     * @return Request
     */
    public function expectedRequest()
    {
        if($this->expectedRequest) {
            return $this->expectedRequest;
        }

        $expectedRequest = new Request($this);
        $this->expectedRequest = $expectedRequest;

        return $expectedRequest;
    }

    /**
     * @param bool $checkContentType
     * @param bool $allowEmptyBody
     *
     * @return $this
     */
    public function json($checkContentType = true, $allowEmptyBody = false)
    {
        return $this->_add(JsonFilter::FILTER_NAME, func_get_args());
    }

    /**
     * @param string              $path
     * @param callable|Expression $expression
     *
     * @return $this
     */
    public function jsonPath($path, $expression)
    {
        return $this->_add(JsonPathFilter::FILTER_NAME, func_get_args());
    }

    /**
     * @param array $conditions
     *
     * Sample schema of $conditions:
     * <pre>
     *   [
     *      [
     *          'sampe.path',
     *          new Expression('equalsTo', [10]),
     *      ],
     *      [
     *          'another[0].path',
     *          function($value) {
     *             return $value !== 200;
     *          }
     *      ]
     *   ]
     * </pre>
     *
     * @return $this
     */
    public function jsonPaths(array $conditions)
    {
        foreach($conditions as $condition) {
            $this->_add(JsonPathFilter::FILTER_NAME, $condition);
        }

        return $this;
    }

    /**
     * @param string              $name
     * @param callable|Expression $expression
     *
     * @return $this
     */
    public function header($name, $expression)
    {
        return $this->_add(HeaderFilter::FILTER_NAME, func_get_args());
    }

    /**
     * @param array $headersConditions
     *
     * @return $this
     */
    public function headers(array $headersConditions)
    {
        foreach($headersConditions as $condition) {
            $this->_add(HeaderFilter::FILTER_NAME, $condition);
        }

        return $this;
    }

    /**
     * @param AbstractResponseItem $item
     * @param null|string          $jsonPath
     * @param null|bool            $strictRequiredValuesMode
     *
     * @return $this
     */
    public function hasItem(AbstractResponseItem $item, $jsonPath = null, $strictRequiredValuesMode = false)
    {
        return $this->_add(HasItemFilter::FILTER_NAME, func_get_args());
    }

    /**
     * @param ResponseItemsCollection $collection
     * @param null|string             $jsonPath
     *
     * @return $this
     */
    public function hasItems(ResponseItemsCollection $collection, $jsonPath = null)
    {
        return $this->_add(HasItemsFilter::FILTER_NAME, func_get_args());
    }

    /**
     * @param $callable
     *
     * @return $this
     */
    public function call($callable)
    {
        return $this->_add(CallFilter::FILTER_NAME, func_get_args());
    }

    /**
     * Check size of response body(in bytes).
     *
     * @param int|Expression $size
     *
     * @return $this
     */
    public function size($size)
    {
        return $this->_add(SizeFilter::FILTER_NAME, func_get_args());
    }

    /**
     * @param Expression $expression
     *
     * @return $this
     */
    public function responseTime(Expression $expression)
    {
        return $this->_add(ResponseTimeFilter::FILTER_NAME, [$expression]);
    }

    /**
     * @param string|Expression $name
     * @param mixed             $value
     * @param mixed             $domain
     * @param mixed             $path
     * @param mixed             $maxAge
     * @param mixed             $expires
     * @param mixed             $secure
     * @param mixed             $discard
     * @param mixed             $httpOnly
     *
     * @return $this
     */
    public function hasCookie(
        $name,
        $value = null,
        $domain = null,
        $path = null,
        $maxAge = null,
        $expires = null,
        $secure = null,
        $discard = null,
        $httpOnly = null
    ){
        return $this->_add(HasCookie::FILTER_NAME, func_get_args());
    }
}