<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ResponseFilters\ContentTypes;

use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ExpressionLanguage\ContainsString;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ResponseFilters\AbstractFilter;
use RestControl\TestCase\ResponseFilters\FilterInterface;
use RestControl\TestCase\ResponseFilters\FilterTrait;

abstract class AbstractContentType extends AbstractFilter implements FilterInterface
{
    use FilterTrait;

    const ERROR_INVALID_VALUE = 1;
    const HTTP_CONTENT_TYPES  = [];

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
     * @return array
     */
    public function getHttpContentTypes()
    {
        return $this::HTTP_CONTENT_TYPES;
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

        foreach($this->getHttpContentTypes() as $requiredContentType) {

            $expression = new Expression(ContainsString::FILTER_NAME, [$requiredContentType]);

            foreach($contentTypes as $contentType) {
                if($this->checkExpression($contentType, $expression)) {
                    return;
                }
            }
        }

        $this->getStatsCollector()
            ->filterError(
                $this,
                self::ERROR_INVALID_VALUE,
                $contentTypes,
                $this->getHttpContentTypes()
            );
    }
}