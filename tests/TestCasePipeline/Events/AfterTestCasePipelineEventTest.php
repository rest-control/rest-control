<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCasePipeline\Events;

use PHPUnit\Framework\TestCase;
use RestControl\TestCasePipeline\Events\AfterTestCasePipelineEvent;
use League\Pipeline\PipelineInterface;
use RestControl\ApiClient\ApiClientInterface;
use RestControl\Loader\TestsBag;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\TestPipelineConfiguration;

class AfterTestCasePipelineEventTest extends TestCase
{
    public function testEvent()
    {
        $pipeline = $this->getMockBuilder(PipelineInterface::class)
            ->getMockForAbstractClass();
        $testsBag = $this->getMockBuilder(TestsBag::class)
            ->disableOriginalConstructor()
            ->getMock();
        $configuration = $this->getMockBuilder(TestPipelineConfiguration::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $apiClient = $this->getMockBuilder(ApiClientInterface::class)
            ->getMockForAbstractClass();
        $payload = $this->getMockBuilder(Payload::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $event = new AfterTestCasePipelineEvent(
            $pipeline,
            $testsBag,
            $configuration,
            $apiClient,
            $payload
        );

        $this->assertSame('after.testCasePipeline', AfterTestCasePipelineEvent::NAME);
        $this->assertSame($pipeline, $event->getPipeline());
        $this->assertSame($testsBag, $event->getTestsBag());
        $this->assertSame($configuration, $event->getConfiguration());
        $this->assertSame($apiClient, $event->getApiClient());
        $this->assertSame($payload, $event->getPayload());
    }
}
