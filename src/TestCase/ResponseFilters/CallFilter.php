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

class CallFilter extends AbstractFilter implements FilterInterface
{
    const FILTER_NAME = 'call';

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        return isset($params[0]) && is_callable($params[0]);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        //todo
    }
}