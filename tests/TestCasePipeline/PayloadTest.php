<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCasePipeline;

use RestControl\ApiClient\ApiClientInterface;
use RestControl\Loader\TestsBag;
use RestControl\TestCasePipeline\Payload;
use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    public function testGetters()
    {
        $apiClientMock = $this->getMockBuilder(ApiClientInterface::class)
            ->getMockForAbstractClass();

        $payload = new Payload(
            $apiClientMock,
            $this->getMockBuilder(TestsBag::class)
                 ->disableOriginalConstructor()
                 ->getMock(),
            'sample tags'
        );

        $this->assertInstanceOf(ApiClientInterface::class, $payload->getApiClient());
        $this->assertInstanceOf(TestsBag::class, $payload->getTestsBag());
        $this->assertSame('sample tags', $payload->getTestsTag());
    }
}