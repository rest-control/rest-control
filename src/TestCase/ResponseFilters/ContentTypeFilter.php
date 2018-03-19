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

class ContentTypeFilter extends AbstractFilter implements FilterInterface
{
    use FilterTrait;

    const FILTER_NAME = 'contentType';
    const ERROR_INVALID_VALUE = 1;

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        return isset($params[0])
            && ($params[0] instanceof Expression || is_callable($params[0]));
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $contentTypes = (array) $apiResponse->getHeader('Content-Type');

        $this->getStatsCollector()
            ->addAssertionsCount();

        foreach($contentTypes as $contentType) {
            if($this->checkExpression($contentType, $params[0])) {
                return;
            }
        }

        $this->getStatsCollector()
            ->filterError(
                $this,
                self::ERROR_INVALID_VALUE,
                $contentTypes,
                $params[0]
            );
    }
}