<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Utils;

use RestControl\ApiClient\ApiClientRequest;

/**
 * Interface MockApiResponseInterface
 */
interface MockApiResponseInterface
{
    /**
     * Returns full endpoint url.
     *
     * e.q. 'get::http://sample.site/sample/endpoint/{id}'
     *
     * @return string
     */
    public function getUrl();

    /**
     * Transform $request into $apiClientResponse.
     *
     * @param ApiClientRequest $request
     * @param array            $routeParams
     *
     * @return \RestControl\ApiClient\ApiClientResponse
     */
    public function getApiClientResponse(ApiClientRequest $request, array $routeParams);
}