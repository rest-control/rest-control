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
 * Class JsonFilter
 *
 * @package RestControl\TestCase\ResponseFilters
 */
class JsonFilter extends AbstractFilter implements FilterInterface
{
    const ERROR_WRONG_CONTENT_TYPE = 1;

    const ERROR_INVALID_BODY = 2;

    /**
     * @return string
     */
    public function getName()
    {
        return 'json';
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        return !isset($params[0]) || is_bool($params[0]);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     *
     * Schema of $params:
     *  - $params[0] boolean, determines is filter should check content type
     *  - $params[1] boolean, determines is filter should allow empty body
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        if(!isset($params[0]) || $params[0]) {
            $this->checkContentType($apiResponse);
        }

        $allowEmptyBody = (!isset($params[1]) || !$params[1]) ? false : true;

        $this->checkBody($apiResponse, $allowEmptyBody);
    }

    /**
     * @param ApiClientResponse $apiResponse
     */
    protected function checkContentType(ApiClientResponse $apiResponse)
    {
        $checked = false;

        foreach($apiResponse->getContentType() as $contentType) {

            $contentType = strtolower($contentType);

            if(preg_match('/application\/json/', $contentType)) {
                $checked = true;
            }
        }

        $this->getStatsCollector()->addAssertionsCount();

        if($checked) {
            return;
        }

        $this->getStatsCollector()->filterError(
            $this,
            self::ERROR_WRONG_CONTENT_TYPE,
            $apiResponse->getContentType(),
            '/application/json/'
        );
    }

    /**
     * @param ApiClientResponse $apiResponse
     */
    protected function checkBody(ApiClientResponse $apiResponse, $allowEmptyBody)
    {
        $this->getStatsCollector()->addAssertionsCount();

        $body = $apiResponse->getBody();

        if($allowEmptyBody && !$body) {
            return;
        }

        json_decode($body);

        if(json_last_error() === JSON_ERROR_NONE) {
           return;
        }

        $this->getStatsCollector()->filterError(
            $this,
            self::ERROR_INVALID_BODY,
            $apiResponse->getBody(),
            'array|json'
        );
    }
}