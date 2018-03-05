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
use Psr\Container\ContainerInterface;
use RestControl\ApiClient\ApiClientInterface;
use RestControl\Loader\TestCaseDelegate;
use RestControl\Loader\TestsBag;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\Stages\PrepareTestsSuiteObjectsStage;
use RestControl\TestCasePipeline\TestObject;
use RestControl\TestCasePipeline\TestSuiteObject;

class PrepareTestsSuiteObjectsStageTest extends TestCase
{
    public function testStage()
    {
        $testsObjects = [
            new TestObject(
                new TestCaseDelegate(
                    SampleTestCase::class,
                    'sampleMethod'
                )
            ),
            new TestObject(
                new TestCaseDelegate(
                    SampleTestCase::class,
                    'anotherMethod'
                )
            ),
            new TestObject(
                new TestCaseDelegate(
                    AnotherTestCase::class,
                    'sampleMethod'
                )
            ),
        ];

        $container = $this->getMockBuilder(ContainerInterface::class)
                          ->disableOriginalConstructor()
                          ->getMock();
        $container->expects($this->any())
                  ->method('get')
                  ->will($this->returnCallback(function($param) {
                      return $this->getMockBuilder($param)
                                  ->disableOriginalConstructor()
                                  ->getMock();
                  }));

        $stage    = new PrepareTestsSuiteObjectsStage($container);

        $testsBag = $this->getMockBuilder(TestsBag::class)
                         ->disableOriginalConstructor()
                         ->getMock();
        $testsBag->expects($this->once())
                 ->method('getTests')
                 ->willReturn($testsObjects);

        $payload = new Payload(
            $this->getMockBuilder(ApiClientInterface::class)
                 ->disableOriginalConstructor()
                 ->getMock(),
            $testsBag
        );

        $stage->__invoke($payload);

        $suites = $payload->getTestsSuiteObjects();

        $this->assertCount(2, $suites);

        $this->assertInstanceOf(TestSuiteObject::class, $suites[0]);
        $this->assertInstanceOf(TestSuiteObject::class, $suites[1]);

        $this->assertSame([
            $testsObjects[0],
            $testsObjects[1],
        ], $suites[0]->getTestsObjects());

        $this->assertSame([
            $testsObjects[2],
        ], $suites[1]->getTestsObjects());

        $this->assertSame($suites[0], $testsObjects[0]->getTestSuiteObject());
        $this->assertSame($suites[0], $testsObjects[1]->getTestSuiteObject());
        $this->assertSame($suites[1], $testsObjects[2]->getTestSuiteObject());
    }
}