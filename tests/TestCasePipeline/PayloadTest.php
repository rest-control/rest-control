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
use RestControl\Loader\TestCaseDelegate;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\TestObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

class PayloadTest extends TestCase
{
    public function testIncorrectTestsDelegate()
    {
        $this->expectException(InvalidArgumentException::class);

        $apiClientMock = $this->getMockBuilder(ApiClientInterface::class)
                              ->getMockForAbstractClass();

        $delegates = [
            new \stdClass(),
        ];

        new Payload(
            $apiClientMock,
            $delegates
        );
    }

    public function testGetters()
    {
        $apiClientMock = $this->getMockBuilder(ApiClientInterface::class)
            ->getMockForAbstractClass();

        $delegates = [
            new TestCaseDelegate(
                'sample',
                'sample'
            ),
        ];

        $payload = new Payload(
            $apiClientMock,
            $delegates
        );

        $this->assertInstanceOf(ApiClientInterface::class, $payload->getApiClient());

        $testCase = $payload->getTestsObjects()[0];
        $this->assertInstanceOf(TestObject::class, $testCase);
        $this->assertSame('sample', $testCase->getDelegate()->getClassName());
    }
}