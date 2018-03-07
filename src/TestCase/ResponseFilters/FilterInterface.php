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
use RestControl\TestCase\StatsCollector\StatsCollectorInterface;

interface FilterInterface
{
    const ERROR_INVALID_PARAMS = -100;
    const ERROR_INTERNAL_EXCEPTION = -101;

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = []);

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = []);

    /**
     * @param StatsCollectorInterface|null $statsCollector
     */
    public function setStatsCollection(StatsCollectorInterface $statsCollector = null);

    /**
     * @return null|StatsCollectorInterface
     */
    public function getStatsCollector();
}