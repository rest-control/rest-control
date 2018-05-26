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

class HasCookie extends AbstractFilter implements FilterInterface
{
    const FILTER_NAME = 'hasCookie';

    const ERROR_CANNOT_FIND_COOKIE = 1;
    const ERROR_INVALID_COOKIE_PARAM_VALUE = 2;

    /**
     * @var array
     */
    protected $cookieMap = [
        'Name',
        'Value',
        'Domain',
        'Path',
        'Max-Age',
        'Expires',
        'Secure',
        'Discard',
        'HttpOnly',
    ];

    /**
     * @param array $params
     *
     *  [0] - Name
     *  [1] - Value
     *  [2] - Domain
     *  [3] - Path
     *  [4] - Max-Age
     *  [5] - Expires
     *  [6] - Secure
     *  [7] - Discard
     *  [8] - HttpOnly
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        if(empty($params)) {
            return false;
        }

        for($i = 0; $i <= 8; $i++) {
            if(isset($params[$i])
                && null !== $params[$i]
                && !$this->isSimpleValueOrExpression($params[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $cookie = $this->findCookie($apiResponse, $params[0]);

        $this->getStatsCollector()
            ->addAssertionsCount();

        if(!$cookie) {
            $this->getStatsCollector()
                ->filterError(
                    $this,
                    self::ERROR_CANNOT_FIND_COOKIE,
                    $cookie,
                    $params[0]
                );

            return;
        }

        for($i = 1; $i <= 8; $i++) {

            if(null === $params[$i] || !isset($this->cookieMap[$i])) {
                continue;
            }

            $cookieParamName = $this->cookieMap[$i];
            $cookieParamValue = isset($cookie[$cookieParamName]) ? $cookie[$cookieParamName] : null;

            if($cookieParamValue === $params[$i] || $this->checkExpression($cookieParamValue, $params[$i])) {
                continue;
            }

            $this->getStatsCollector()
                ->filterError(
                    $this,
                    self::ERROR_INVALID_COOKIE_PARAM_VALUE,
                    $cookieParamValue,
                    $params[$i]
                );

            return;
        }
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param mixed             $cookieNameExpression
     *
     * @return null|array
     */
    protected function findCookie(ApiClientResponse $apiResponse, $cookieNameExpression)
    {
        foreach($apiResponse->getCookies() as $cookie) {

            if(!isset($cookie['Name'])) {
                continue;
            }

            if($cookieNameExpression === $cookie['Name'] || $this->checkExpression($cookie['Name'], $cookieNameExpression)) {
                return $cookie;
            }
        }

        return null;
    }
}