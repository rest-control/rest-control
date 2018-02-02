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

use PHPUnit\Framework\TestCase;
use RestControl\ApiClient\ApiClientInterface;
use RestControl\ApiClient\ApiClientResponse;
use RestControl\Loader\TestCaseDelegate;
use RestControl\TestCase\ResponseFilters\FilterException;
use RestControl\TestCase\ResponseFilters\JsonPathFilter;
use RestControl\TestCase\ResponseFiltersBag;
use RestControl\TestCasePipeline\Events\AfterTestCaseEvent;
use RestControl\TestCasePipeline\Events\BeforeTestCaseEvent;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\Stages\RunTestObjectsStage;
use RestControl\TestCasePipeline\TestObject;
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

        $payload = $this->getMockBuilder(Payload::class)
                        ->disableOriginalConstructor()
                        ->getMock();
        $payload->expects($this->once())
                ->method('getTestsObjects')
                ->willReturn([]);

        $stage = new RunTestObjectsStage(
            $responseFiltersBag,
            $eventDispatcher
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

        $responseFiltersBag = $this->getMockBuilder(ResponseFiltersBag::class)
                                   ->disableOriginalConstructor()
                                   ->getMock();
        $responseFiltersBag->expects($this->once())
                           ->method('filterResponse')
                           ->willReturn([
                               new FilterException(
                                   new JsonPathFilter(),
                                   'sample error'
                               )
                           ]);

        $stage = new RunTestObjectsStage(
            $responseFiltersBag,
            $eventDispatcher
        );

        $stage->__invoke($payload);

        $this->assertSame(0, $testObject->getQueueIndex());
    }
}