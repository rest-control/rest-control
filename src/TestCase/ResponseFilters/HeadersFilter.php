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

/**
 * Class HeadersFilter
 * @package RestControl\TestCase\ResponseFilters
 */
class HeadersFilter implements FilterInterface
{
    const ERROR_EXPRESSION = 1;

    /**
     * @return string
     */
    public function getName()
    {
        return 'headers';
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
       if(!isset($params[0]) || !is_array($params[0])) {
           return false;
       }

       return true;
    }

    /**
     * @param ApiClientResponse $apiResponse
     *
     * @param array $params <pre>
     *    [
     *      [
     *          'headerName' => 'expression'
     *      ]
     *    ]
     * </pre>
     *
     * @throws FilterException
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        foreach($params[0] as $headerName => $headerExpression) {

            if(is_array($headerExpression)){
                foreach($headerExpression as $expression) {
                    $this->check($apiResponse, $headerName, $expression);
                }

                continue;
            }

            $this->check($apiResponse, $headerName, $headerExpression);
        }
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param $headerName
     * @param $value
     *
     * @throws FilterException
     */
    protected function check(ApiClientResponse $apiResponse, $headerName, $value)
    {
        foreach($apiResponse->getHeader($headerName) as $header) {

            if($header !== $value) {

                throw new FilterException(
                    $this,
                    self::ERROR_EXPRESSION,
                    $apiResponse->getHeader($headerName),
                    $value
                );
            }
        }
    }
}