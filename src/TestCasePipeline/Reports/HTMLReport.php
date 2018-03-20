<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline\Reports;

use RestControl\TestCasePipeline\Payload;
use League\Plates\Engine;

class HTMLReport implements ReportInterface
{
    /**
     * @var Engine
     */
    protected $templateEngine;

    /**
     * HTMLReport constructor.
     */
    public function __construct()
    {
        $this->templateEngine = Engine::create(
            dirname(__FILE__) . '/assets/',
            'phtml'
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'html';
    }

    /**
     * @param Payload $payload
     * @param string  $reportDir
     */
    public function report(Payload $payload, $reportDir)
    {
        $this->makeTestSuiteListFiles($payload, $reportDir);
        $this->makeTestSuiteFiles($payload, $reportDir);
    }

    /**
     * @param Payload $payload
     * @param string  $reportDir
     */
    protected function makeTestSuiteListFiles(Payload $payload, $reportDir)
    {
        $namespaces = [];

        foreach($payload->getTestsSuiteObjects() as $object) {
            /** @var \RestControl\TestCasePipeline\TestSuiteObject $object */
            $classParts = explode('\\', get_class($object->getSuite()));
            $classPath = '';

            foreach($classParts as $part) {

                if($classPath) {
                    $classPath .= '\\';
                }

                $classPath .= $part;

                if($classPath === get_class($object->getSuite())) {
                    break;
                }

                if(in_array($classPath, $namespaces)) {
                    continue;
                }

                $namespaces []= $classPath;

                $this->makeTestSuiteNamespaceList($payload, $reportDir, $classPath);
            }
        }
    }

    /**
     * @param Payload $payload
     * @param string  $reportDir
     * @param string  $namespace
     */
    protected function makeTestSuiteNamespaceList(Payload $payload, $reportDir, $namespace)
    {
        $suites = [];

        foreach($payload->getTestsSuiteObjects() as $object) {
            /** @var \RestControl\TestCasePipeline\TestSuiteObject $object */
            if(strpos(get_class($object->getSuite()), $namespace) !== 0) {
                continue;
            }

            $suites []= $object;
        }

        $template = $this->templateEngine->render('tests-dir', [
            'suites'    => $suites,
            'namespace' => $namespace,
        ]);

        $fileName = 'namespace_' . $this->generateFileNameFromString($namespace);

        file_put_contents($reportDir . DIRECTORY_SEPARATOR . $fileName . '.html', $template);
    }

    /**
     * @param Payload $payload
     * @param string  $reportDir
     */
    protected function makeTestSuiteFiles(Payload $payload, $reportDir)
    {
        foreach($payload->getTestsSuiteObjects() as $object) {
            /** @var \RestControl\TestCasePipeline\TestSuiteObject $object */
            $template = $this->templateEngine->render('test-suite', [
                'tests' => $object->getTestsObjects(),
                'suite' => $object->getSuite(),
            ]);

            $fileName = $this->generateFileNameFromClass($object->getSuite());

            file_put_contents($reportDir . DIRECTORY_SEPARATOR . $fileName . '.html', $template);
        }
    }

    /**
     * @param $class
     *
     * @return string
     */
    protected function generateFileNameFromClass($class)
    {
        $fileName = get_class($class);

        return $this->generateFileNameFromString($fileName);
    }

    /**
     * @param $string
     *
     * @return string
     */
    protected function generateFileNameFromString($string)
    {
        return strtolower(str_replace('\\', '_', $string));
    }
}