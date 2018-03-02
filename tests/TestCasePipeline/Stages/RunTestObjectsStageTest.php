<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCasePipeline\Stages;

use League\Container\Container;
use League\Container\ReflectionContainer;
use PHPUnit\Framework\TestCase;
use RestControl\ApiClient\ApiClientInterface;
use RestControl\ApiClient\ApiClientResponse;
use RestControl\Loader\TestCaseDelegate;
use RestControl\TestCase\ResponseFiltersBag;
use RestControl\TestCase\StatsCollector\StatsCollector;
use RestControl\TestCasePipeline\Events\AfterTestCaseEvent;
use RestControl\TestCasePipeline\Events\BeforeTestCaseEvent;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\Stages\RunTestObjectsStage;
use RestControl\TestCasePipeline\TestObject;
use RestControl\TestCasePipeline\TestPipelineConfiguration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RunTestObjectsStageTest extends TestCase
{
    public function testInvokeReturn()
    {
        $responseFiltersBag = $this->getMockBuilder(ResponseFiltersBag::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
                                ->getMockForAbstractClass();

        $container = new Container();
        $container->delegate(new ReflectionContainer());

        $payload = $this->getMockBuilder(Payload::class)
                        ->disableOriginalConstructor()
                        ->getMock();
        $payload->expects($this->once())
                ->method('getTestsObjects')
                ->willReturn([]);

        $stage = new RunTestObjectsStage(
            $responseFiltersBag,
            $eventDispatcher,
            $container
        );

        $this->assertInstanceOf(Payload::class, $stage->__invoke(
            $payload
        ));
    }
    
    public function testRunTestsObject()
    {
        $testCaseDelegate = new TestCaseDelegate(
            SampleTestCase::class,
            'sampleTest'
        );

        $apiClientResponse = new ApiClientResponse(200, [], '');

        $apiClient = $this->getMockBuilder(ApiClientInterface::class)
                          ->getMockForAbstractClass();
        $apiClient->expects($this->once())
                  ->method('send')
                  ->willReturn($apiClientResponse);

        $payload    = new Payload($apiClient, [$testCaseDelegate]);

        $this->assertNotEmpty($payload->getTestsObjects());

        /** @var TestObject $testObject */
        $testObject = $payload->getTestsObjects()[0];

        $this->assertInstanceOf(TestObject::class, $testObject);

        $eventDispatcher = $this->getMockBuilder(EventDispatcherInterface::class)
                                ->getMockForAbstractClass();

        $eventDispatcher->expects($this->exactly(2))
                        ->method('dispatch')
                        ->withConsecutive(
                            [BeforeTestCaseEvent::NAME],
                            [AfterTestCaseEvent::NAME]
                        );

        $statsCollector = new StatsCollector();

        $responseFiltersBag = $this->getMockBuilder(ResponseFiltersBag::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $responseFiltersBag->expects($this->once())
                           ->method('filterResponse')
                           ->willReturn($statsCollector);

        $container = new Container();
        $container->delegate(new ReflectionContainer());
        $container->add(TestPipelineConfiguration::class, new TestPipelineConfiguration([
            'tests' => [
                'namespace' => 'Sample\\',
                'path'      => 'sample',
            ],
        ]));

        $stage = new RunTestObjectsStage(
            $responseFiltersBag,
            $eventDispatcher,
            $container
        );

        $stage->__invoke($payload);

        $this->assertSame(0, $testObject->getQueueIndex());
    }
}