<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\ApiClient;

/**
 * Interface ApiClientInterface
 *
 * @package RestControl\ApiClient
 */
interface ApiClientInterface
{
    /**
     * @param ApiClientRequest $request
     *
     * @return ApiClientResponse
     */
    public function send(ApiClientRequest $request);
}