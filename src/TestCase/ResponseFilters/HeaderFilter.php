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
use RestControl\TestCase\ExpressionLanguage\Expression;

class HeaderFilter extends AbstractFilter implements FilterInterface
{
    use FilterTrait;

    const FILTER_NAME         = 'header';
    const ERROR_INVALID_VALUE = 1;

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
       if(!isset($params[0]) || !is_string($params[0])) {
           return false;
       }

       if(!isset($params[1]) || (!$params[1] instanceof Expression && !is_callable($params[1]))) {
           return false;
       }

       return true;
    }

    /**
     * @param ApiClientResponse $apiResponse
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $header = $apiResponse->getHeader($params[0]);

        $expectedValue  = false;

        if(!is_array($header)) {
            $expectedValue = $this->checkExpression($header, $params[1]);
        } else {
            foreach($header as $value) {

                $expectedValue = $this->checkExpression($value, $params[1]);

                if($expectedValue) {
                    return;
                }
            }
        }

        $this->getStatsCollector()
             ->addAssertionsCount();

        if($expectedValue) {
           return;
        }

        $this->getStatsCollector()
             ->filterError(
                 $this,
                 self::ERROR_INVALID_VALUE,
                 $header,
                 $params[1]
             );
    }
}