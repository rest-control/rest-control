<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\ApiClient;

use PHPUnit\Framework\TestCase;
use RestControl\ApiClient\ApiClientResponse;

class ApiClientResponseTest extends TestCase
{
    public function testGetters()
    {
        $response = new ApiClientResponse(
            200,
            [
                'Sample' => [
                    'sample-value',
                    'another-sample-value',
                ],
                'Another' => [
                    'another-value',
                ],
            ],
            '{"status":"ok"}',
            123,
            234
        );


        $this->assertSame(
            200,
            $response->getStatusCode()
        );

        $this->assertSame(
            [
                'sample-value',
                'another-sample-value',
            ],
            $response->getHeader('Sample')
        );

        $this->assertSame(
            [
                'Sample' => [
                    'sample-value',
                    'another-sample-value',
                ],
                'Another' => [
                    'another-value',
                ],
            ],
            $response->getHeaders()
        );

        $this->assertSame(
            '{"status":"ok"}',
            $response->getBody()
        );

        $this->assertSame(
            123,
            $response->getBodySize()
        );

        $this->assertSame(
            234,
            $response->getResponseTime()
        );
    }
}