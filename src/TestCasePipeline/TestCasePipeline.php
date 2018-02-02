<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline;

use Composer\Autoload\ClassLoader;
use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Pipeline\Pipeline;
use Psr\Container\ContainerInterface;
use RestControl\Loader\ComposerClassMapLoader;
use RestControl\Loader\TestsBag;
use RestControl\TestCase\ResponseFiltersBag;
use RestControl\TestCasePipeline\Stages\RunTestObjectsStage;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class TestCasePipeline
 *
 * @package RestControl\TestCasePipeline
 */
class TestCasePipeline
{
    /**
     * @var null|Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $pipelineStages = [
        RunTestObjectsStage::class
    ];

    /**
     * TestCasePipeline constructor.
     *
     * @param ClassLoader               $classLoader
     * @param TestPipelineConfiguration $configuration
     */
    public function __construct(
        ClassLoader $classLoader,
        TestPipelineConfiguration $configuration
    ){
        $this->prepareContainer($classLoader, $configuration);
        $this->prepareTestsBag($classLoader, $configuration);
        $this->preparePipeline();
    }

    /**
     * @return Payload
     */
    public function process()
    {
        /** @var Pipeline $pipeline */
        $pipeline = $this->container->get(Pipeline::class);
        /** @var TestsBag $testsBag */
        $testsBag = $this->container->get(TestsBag::class);
        /** @var TestPipelineConfiguration $configuration */
        $configuration = $this->container->get(TestPipelineConfiguration::class);

        $apiClient = $this->container->get($configuration->getApiClient());

        $payload = new Payload(
            $apiClient,
            $testsBag->getTests()
        );

        return $pipeline->process($payload);
    }

    /**
     * @return Container|null
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param EventSubscriberInterface $subscriber
     *
     * @return $this
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
       $this->container->get(EventDispatcherInterface::class)
                       ->ad($subscriber);

       return $this;
    }

    /**
     * @param ClassLoader               $loader
     * @param TestPipelineConfiguration $configuration
     */
    protected function prepareTestsBag(ClassLoader $loader, TestPipelineConfiguration $configuration)
    {
        /** @var TestsBag $testsBag */
        $testsBag = $this->container->get(TestsBag::class);

        $testsBag->addLoader(
            new ComposerClassMapLoader(
                $loader,
                $configuration->getTestsNamespaces()
            )
        );
    }

    /**
     * Prepare test cases pipeline.
     */
    protected function preparePipeline()
    {
        $stages = [];

        foreach($this->pipelineStages as $stage) {
            $stages []= $this->container->get($stage);
        }

        $pipeline = new Pipeline($stages);

        $this->container->share(
            Pipeline::class,
            function() use($pipeline){
                return $pipeline;
            }
        );
    }

    /**
     * @param ClassLoader               $classLoader
     * @param TestPipelineConfiguration $configuration
     */
    protected function prepareContainer(ClassLoader $classLoader, TestPipelineConfiguration $configuration)
    {
        $this->container = new Container();
        $this->container->delegate(new ReflectionContainer());

        $this->container->share(ClassLoader::class, $classLoader);
        $this->container->share(ContainerInterface::class, $this->container);
        $this->container->share(TestPipelineConfiguration::class, $configuration);
        $this->container->share(ResponseFiltersBag::class, new ResponseFiltersBag());
        $this->container->share(EventDispatcherInterface::class, new EventDispatcher());
        $this->container->share(
            TestsBag::class,
            $this->container->get(TestsBag::class)
        );
    }
}