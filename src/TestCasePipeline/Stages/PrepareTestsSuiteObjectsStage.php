<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline\Stages;

use Psr\Container\ContainerInterface;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\TestObject;
use RestControl\TestCasePipeline\TestSuiteObject;

class PrepareTestsSuiteObjectsStage
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * PrepareTestsSuiteObjectsStage constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    /**
     * @param Payload $payload
     *
     * @return Payload
     */
    public function __invoke(Payload $payload)
    {
        $testsBag = $payload->getTestsBag();

        $suites = $this->prepareTestSuiteObjects(
            $testsBag->getTests($payload->getTestsTag())
        );

        $payload->setTestSuiteObjects($suites);

        return $payload;
    }

    /**
     * @param array $testsObjectDelegates
     *
     * @return array
     */
    protected function prepareTestSuiteObjects(array $testsObjectDelegates)
    {
        $groups = $this->groupTestsObjects($testsObjectDelegates);
        $suites = [];

        foreach($groups as $testObjectsGroupName => $groupObjects) {

            $testSuiteObject = new TestSuiteObject(
                $this->container->get($testObjectsGroupName)
            );

            $testSuiteObject->addTestObjects($groupObjects);

            $suites []= $testSuiteObject;
        }

        return $suites;
    }

    /**
     * @param array $testsObjectDelegates
     *
     * @return array
     */
    protected function groupTestsObjects(array $testsObjectDelegates)
    {
        $groups = [];

        foreach($testsObjectDelegates as $testsObjectDelegate) {

            if(!isset($groups[$testsObjectDelegate->getClassName()])) {
                $groups[$testsObjectDelegate->getClassName()] = [];
            }

            $groups[$testsObjectDelegate->getClassName()] []= new TestObject($testsObjectDelegate);
        }

        return $groups;
    }
}