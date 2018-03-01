<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Loader;

use Psr\Container\ContainerInterface;

class TestsBag
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $loaders = [];

    /**
     * @var array
     */
    protected $compiledTests = null;

    /**
     * TestsBag constructor.
     *
     * @param ContainerInterface $container
     * @param array              $loaders
     */
    public function __construct(ContainerInterface $container, array $loaders = [])
    {
        $this->container = $container;

        foreach($loaders as $loader) {

            if(!$loader instanceof LoaderInterface) {
                throw new \InvalidArgumentException('Loader must be instance of \RestControl\Loader\LoaderInterface');
            }

            $this->loaders []= $loader;
        }
    }

    /**
     * @param LoaderInterface $loader
     */
    public function addLoader(LoaderInterface $loader)
    {
        $this->loaders []= $loader;
    }

    /**
     * Compile all test classes.
     */
    public function compileTests()
    {
        $delegates = [];

        foreach($this->loaders as $loader)
        {
            /** @var LoaderInterface $loader */
            $classes = (array) $loader->load();

            foreach($classes as $class) {

                if(!$class instanceof TestCaseDelegate) {
                    throw new \InvalidArgumentException('Test class representation must be instance of TestCaseDelegate.');
                }

                $delegates []= $class;
            }
        }

        $this->compiledTests = $delegates;
    }

    /**
     * Returns array of test case delegates.
     *
     * $tags = 'sample,function' - returns all tests with "sample" or/and "function" tags.
     * $tags = 'sample function' - returns all tests with "sample" and "function" tags.
     *
     * @param string $tags
     *
     * @return array
     */
    public function getTests($tags = '')
    {
        if(null === $this->compiledTests) {
            $this->compileTests();
        }

        return $this->getByTags($tags);
    }

    /**
     * @param string $tags
     *
     * @return array
     */
    protected function getByTags($tags)
    {
        if(!$this->compiledTests) {
            return [];
        }

        if(empty($tags)) {
            return $this->compiledTests;
        }

        $groups = $this->parseTagStringIntoGroups($tags);

        if(empty($groups)) {
            return $this->compiledTests;
        }

        $delegates  = [];

        foreach($this->compiledTests as $test) {
            /** @var TestCaseDelegate $test */
            if(!$this->checkTestTags($test, $groups)) {
                continue;
            }

            $delegates []= $test;
        }

        return $delegates;
    }

    /**
     * @param TestCaseDelegate $test
     * @param array            $tagsGroups
     *
     * @return bool
     */
    protected function checkTestTags(TestCaseDelegate $test, array $tagsGroups)
    {
        $testTags = $test->getTags();

        foreach($tagsGroups as $group) {
            if(count(array_diff($group, $testTags)) === count($group)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $tags
     *
     * @return array
     */
    protected function parseTagStringIntoGroups($tags)
    {
        $parts     = explode(' ', $tags);
        $tagGroups = [];

        foreach($parts as $part) {

            $tagsAlternatives = [];

            foreach(explode(',', $part) as $alternative) {
                $alternative = preg_replace('/\s+/', '', $alternative);
                $tagsAlternatives []= $alternative;
            }

            $tagGroups []= $tagsAlternatives;
        }

        return $tagGroups;
    }
}