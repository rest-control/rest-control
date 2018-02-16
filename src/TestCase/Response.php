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
use RestControl\Utils\AbstractResponseItem;

/**
 * Class Response
 *
 * @package RestControl\TestCase
 */
class Response extends AbstractChain
{
    const CO_JSON = 'json';
    const CO_JSON_PATH = 'jsonPath';
    const CO_HEADER = 'header';
    const CO_HAS_ITEM = 'hasItem';

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
        return $this->_add(self::CO_JSON, func_get_args());
    }

    /**
     * @param string              $path
     * @param callable|Expression $expression
     *
     * @return $this
     */
    public function jsonPath($path, $expression)
    {
        return $this->_add(self::CO_JSON_PATH, func_get_args());
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
            $this->_add(self::CO_JSON_PATH, $condition);
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
        return $this->_add(self::CO_HEADER, func_get_args());
    }

    /**
     * @param array $headersConditions
     *
     * @return $this
     */
    public function headers(array $headersConditions)
    {
        foreach($headersConditions as $condition) {
            $this->_add(self::CO_HEADER, $condition);
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
        return $this->_add(self::CO_HAS_ITEM, func_get_args());
    }
}