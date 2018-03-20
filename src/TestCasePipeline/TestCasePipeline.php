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
use RestControl\Loader\PsrClassLoader;
use RestControl\Loader\TestsBag;
use RestControl\TestCase\ResponseFiltersBag;
use RestControl\TestCasePipeline\Events\AfterTestCasePipelineEvent;
use RestControl\TestCasePipeline\Events\BeforeTestCasePipelineEvent;
use RestControl\TestCasePipeline\Reports\HTMLReport;
use RestControl\TestCasePipeline\Stages\PrepareTestsSuiteObjectsStage;
use RestControl\TestCasePipeline\Stages\ReportStage;
use RestControl\TestCasePipeline\Stages\RunTestObjectsStage;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use RestControl\TestCasePipeline\Reports\FactoryInterface as ReportsFactoryInterface;
use RestControl\TestCasePipeline\Reports\Factory as ReportsFactory;
use RestControl\TestCasePipeline\Reports\JsonReport;

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
        PrepareTestsSuiteObjectsStage::class,
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
    }

    /**
     * @param string $report
     * @param string $reportDir
     *
     * @return $this
     */
    public function addReport($report, $reportDir)
    {
        /** @var ReportsFactoryInterface $factory */
        $factory = $this->container->get(ReportsFactoryInterface::class);
        $report  = $factory->get($report);

        $this->pipelineStages []= new ReportStage(
            $report,
            $reportDir
        );

        return $this;
    }

    /**
     * $tags = 'sample,function' - returns all tests with "sample" or/and "function" tags.
     * $tags = 'sample function' - returns all tests with "sample" and "function" tags.
     *
     * @param string $tags
     *
     * @return Payload
     */
    public function process($tags = '')
    {
        /** @var Pipeline $pipeline */
        $pipeline = $this->preparePipeline();
        /** @var TestsBag $testsBag */
        $testsBag = $this->container->get(TestsBag::class);
        /** @var TestPipelineConfiguration $configuration */
        $configuration = $this->container->get(TestPipelineConfiguration::class);

        $apiClient = $this->container->get($configuration->getApiClient());

        $this->container->get(EventDispatcherInterface::class)
            ->dispatch(
                BeforeTestCasePipelineEvent::NAME,
                new BeforeTestCasePipelineEvent(
                    $pipeline,
                    $testsBag,
                    $configuration,
                    $apiClient
                )
            );

        $payload = new Payload(
            $apiClient,
            $testsBag,
            $tags
        );

        $processedPayload = $pipeline->process($payload);

        $this->container->get(EventDispatcherInterface::class)
            ->dispatch(
                AfterTestCasePipelineEvent::NAME,
                new AfterTestCasePipelineEvent(
                    $pipeline,
                    $testsBag,
                    $configuration,
                    $apiClient,
                    $payload
                )
            );

        return $processedPayload;
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
            ->addSubscriber($subscriber);

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
            new PsrClassLoader(
                $configuration->getTestsNamespace()
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

            if(is_object($stage)) {
                $stages []= $stage;
                continue;
            }

            $stages []= $this->container->get($stage);
        }

        return new Pipeline($stages);
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

        $this->prepareResponseFiltersBag($configuration);

        $this->container->share(EventDispatcherInterface::class, new EventDispatcher());
        $this->container->share(
            TestsBag::class,
            $this->container->get(TestsBag::class)
        );

        $this->container->share(ReportsFactoryInterface::class, function() {

            $factory = new ReportsFactory();
            $factory->addReport(new JsonReport());
            $factory->addReport(new HTMLReport());

            return $factory;
        });
    }

    /**
     * @param TestPipelineConfiguration $configuration
     */
    protected function prepareResponseFiltersBag(TestPipelineConfiguration $configuration)
    {
        $responseFiltersBag = new ResponseFiltersBag();
        $responseFilters    = [];

        foreach($configuration->getResponseFilters() as $responseFilter) {
            $responseFilters []= $this->container->get($responseFilter);
        }

        $responseFiltersBag->addFilters($responseFilters);

        $this->container->share(ResponseFiltersBag::class, $responseFiltersBag);
    }
}