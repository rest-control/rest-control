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
 * Class Response
 *
 * @package RestControl\TestCase
 */
class Response extends AbstractChain
{
    const CO_JSON = 'json';
    const CO_JSON_PATH = 'jsonPath';
    const CO_HEADER = 'header';
    const CO_EXPECTED_REQUEST = 'expectedRequest';

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

        $this->_add(self::CO_EXPECTED_REQUEST, [$expectedRequest]);
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

        $this->_add(self::CO_EXPECTED_REQUEST, [$expectedRequest]);
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
     * @param string $path
     * @param string $expression
     * @param mixed  $expectedValue
     *
     * @return $this
     */
    public function jsonPath($path, $expression, $expectedValue)
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
     *          '=',
     *          123,
     *      ],
     *      [
     *          'another[0].path',
     *          '!=',
     *          934,
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
}