<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ResponseFilters\HttpCodes;

use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ResponseFilters\AbstractFilter;
use RestControl\TestCase\ResponseFilters\FilterInterface;

abstract class AbstractPredefinedHttpCode extends AbstractFilter implements FilterInterface
{
    const INVALID_STATUS_CODE = 1;
    const HTTP_STATUS_CODE    = null;

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        return true;
    }

    /**
     * @return string
     */
    public function getHttpStatusCode()
    {
        return $this::HTTP_STATUS_CODE;
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $this->getStatsCollector()
            ->addAssertionsCount();

        if($this::HTTP_STATUS_CODE === $apiResponse->getStatusCode()) {
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