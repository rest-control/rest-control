<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ResponseFilters;

use RestControl\ApiClient\ApiClientResponse;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class JsonPathFilter
 *
 * @package RestControl\TestCase\ResponseFilters
 */
class JsonPathFilter implements FilterInterface
{
    const ERROR_WRONG_BODY_FORMAT = 1;
    const ERROR_INVALID_VALUE = 2;

    protected static $accessor = null;

    /**
     * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    protected static function getAccessor()
    {
        if(!self::$accessor) {
            self::$accessor = PropertyAccess::createPropertyAccessorBuilder()
                ->getPropertyAccessor();
        }

        return self::$accessor;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'jsonPath';
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        if(count($params) !== 3) {
            return false;
        }

        return is_string($params[0]) && is_string($params[1]);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     *
     * Schema of $params:
     *  - $params[0] string, json path
     *  - $params[1] string, expression
     *  - $params[2] mixed, expected value
     *
     * @throws FilterException
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $body = $apiResponse->getBody();

        if(is_object($body)) {
            $body = json_decode(json_encode($body), true);
        } else {
            $body = json_decode($body, true);
        }

        if(!$body) {
            throw new FilterException(
                $this,
                self::ERROR_WRONG_BODY_FORMAT,
                $apiResponse->getBody(),
                'array|object|json_string'
            );
        }


        $pathParts = explode('.', $params[0]);
        $path = '';

        foreach($pathParts as $part) {
            $path .= '[' . $part . ']';
        }

        $this->check(
            self::getAccessor()->getValue($body, $path),
            $params[1],
            $params[2]
        );
    }

    /**
     * @param $value
     * @param $expression
     * @param $expected
     *
     * @throws FilterException
     */
    protected function check($value, $expression, $expected)
    {
        $ok = false;

        switch($expression) {
            case '==':
            case '=':
                $ok = ($value == $expected);
                break;
            case '===':
                $ok = ($value === $expected);
                break;
            case '!=':
                $ok = ($value != $expected);
                break;
            case '>':
                $ok = ($value > $expected);
                break;
            case '<':
                $ok = ($value < $expected);
                break;
        }

        if(!$ok) {
            throw new FilterException(
                $this,
                self::ERROR_INVALID_VALUE,
                $value,
                '$value ' . $expression . ' ' . $expected
            );
        }
    }
}