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

class ResponseTimeFilter extends AbstractFilter implements FilterInterface
{
    const FILTER_NAME = 'responseTime';

    const ERROR_RESPONSE_TIME_MISMATCH = 1;

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        return isset($params[0])
            && $params[0] instanceof Expression;
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $this->getStatsCollector()->addAssertionsCount();

        if($this->checkExpression(
            $apiResponse->getResponseTime(),
            $params[0]
        )) {
            return;
        }

        $this->getStatsCollector()->filterError(
            $this,
            self::ERROR_RESPONSE_TIME_MISMATCH,
            $apiResponse->getResponseTime(),
            $params[0]
        );
    }
}