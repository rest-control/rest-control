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

class SizeFilter extends AbstractFilter implements FilterInterface
{
    const FILTER_NAME = 'size';

    const ERROR_INVALID_VALUE = 1;

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        return isset($params[0]) &&
            (is_numeric($params[0]) || $params[0] instanceof Expression);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $bodySize = $apiResponse->getBodySize();

        $this->getStatsCollector()
            ->addAssertionsCount();

        if($params[0] instanceof Expression) {
            $validSize = $this->checkExpression($bodySize, $params[0]);
        } else if($bodySize === $params[0]){
            $validSize = true;
        } else {
            $validSize = false;
        }

        if($validSize) {
            return;
        }

        $this->getStatsCollector()
            ->filterError(
                $this,
                self::ERROR_INVALID_VALUE,
                $bodySize,
                $params[0]
            );
    }
}