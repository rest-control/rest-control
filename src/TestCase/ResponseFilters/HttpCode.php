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
 * Class HttpCode
 *
 * @package RestControl\TestCase\ResponseFilters
 */
class HttpCode extends AbstractFilter implements FilterInterface
{
    const FILTER_NAME         = 'httpCode';
    const INVALID_STATUS_CODE = 1;

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        return isset($params[0]) && is_scalar($params[0]);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     *
     * Schema of $params:
     *  - $params[0] scalar value, http code
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $this->getStatsCollector()
             ->addAssertionsCount();

        if($apiResponse->getStatusCode() === $params[0]) {
            return;
        }

        $this->getStatsCollector()
             ->filterError(
                 $this,
                 self::INVALID_STATUS_CODE,
                 $apiResponse->getStatusCode(),
                 $params[0]
             );
    }
}