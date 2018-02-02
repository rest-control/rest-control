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

use Composer\Autoload\ClassLoader;
use League\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use RestControl\ApiClient\HttpGuzzleClient;
use RestControl\Loader\TestsBag;
use RestControl\TestCase\ResponseFiltersBag;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\TestCasePipeline;
use RestControl\TestCasePipeline\TestPipelineConfiguration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TestCasePipelineTest extends TestCase
{
    public function testPipelinePreparationProcess()
    {
        $loader = $this->getMockBuilder(ClassLoader::class)
                       ->disableOriginalConstructor()
                       ->getMock();
        $configuration = $this->getMockBuilder(TestPipelineConfiguration::class)
                              ->getMock();
        $configuration->expects($this->once())
                      ->method('getTestsNamespaces')
                      ->willReturn([]);

        $pipeline = new TestCasePipeline(
            $loader,
            $configuration
        );

        $di = $pipeline->getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $di);

        $this->assertInstanceOf(
            ClassLoader::class,
            $di->get(ClassLoader::class)
        );

        $this->assertInstanceOf(
            ContainerInterface::class,
            $di->get(ContainerInterface::class)
        );

        $this->assertInstanceOf(
            TestPipelineConfiguration::class,
            $di->get(TestPipelineConfiguration::class)
        );

        $this->assertInstanceOf(
            ResponseFiltersBag::class,
            $di->get(ResponseFiltersBag::class)
        );

        $this->assertInstanceOf(
            EventDispatcherInterface::class,
            $di->get(EventDispatcherInterface::class)
        );

        $this->assertInstanceOf(
            TestsBag::class,
            $di->get(TestsBag::class)
        );

        $this->assertInstanceOf(
            Pipeline::class,
            $di->get(Pipeline::class)
        );
    }

    public function testProcess()
    {

        $loader = $this->getMockBuilder(ClassLoader::class)
            ->disableOriginalConstructor()
            ->getMock();
        $configuration = $this->getMockBuilder(TestPipelineConfiguration::class)
            ->getMock();
        $configuration->expects($this->once())
            ->method('getTestsNamespaces')
            ->willReturn([]);
        $configuration->expects($this->once())
            ->method('getApiClient')
            ->willReturn(HttpGuzzleClient::class);

        $pipeline = new TestCasePipeline(
            $loader,
            $configuration
        );

        $this->assertInstanceOf(Payload::class, $pipeline->process());
    }
}