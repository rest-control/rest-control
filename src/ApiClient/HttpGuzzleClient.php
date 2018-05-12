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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

class HttpGuzzleClient implements ApiClientInterface
{
    /**
     * @param ApiClientRequest $schema
     *
     * @return ApiClientResponse
     */
    public function send(ApiClientRequest $schema)
    {
        $client = new Client();

        try{

            $response = $client->request(
                strtoupper($schema->getMethod()),
                $this->getUrl($schema),
                $this->buildOptions($schema)
            );

        } catch ( ClientException $e) {
            $response = $e->getResponse();
        }

        return $this->parseResponse($response);
    }

    /**
     * @param ApiClientRequest $schema
     *
     * @return array
     */
    protected function buildOptions(ApiClientRequest $schema)
    {
        $options = [
            'query'       => $this->getQueryParams($schema),
            'form_params' => $schema->getFormParams(),
            'headers'     => $schema->getHeaders(),
        ];

        return $options;
    }

    /**
     * @param ApiClientRequest $schema
     *
     * @return array
     */
    protected function getQueryParams(ApiClientRequest $schema)
    {
        $queryParams = $schema->getUrlParams();
        $url = $schema->getUrl();

        foreach($schema->getUrlParams() as $name => $value) {

            $matches = [];
            preg_match('/{'.$name.'}/', $url, $matches);

            foreach($matches as $match) {
                $match = str_replace('{', '', $match);
                $match = str_replace('}', '', $match);
                unset($queryParams[$match]);
            }
        }

        return $queryParams;
    }

    /**
     * @param ApiClientRequest $schema
     *
     * @return string
     */
    protected function getUrl(ApiClientRequest $schema)
    {
        $url = $schema->getUrl();

        foreach($schema->getUrlParams() as $name => $value) {
            $url = str_replace('{' . $name . '}', $value, $url);
        }

        return $url;
    }

    /**
     * @param Response $response
     *
     * @return ApiClientResponse
     */
    protected function parseResponse(Response $response)
    {
        return new ApiClientResponse(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody()->getContents(),
            $response->getBody()->getSize()
        );
    }
}