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

use League\Container\Container;

/**
 * Class TestsBag
 *
 * @package RestControl\Loader
 */
class TestsBag
{
    /**
     * @var Container
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
     * @param Container $container
     * @param array     $loaders
     */
    public function __construct(Container $container, array $loaders = [])
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
     * @return array
     */
    public function getTests()
    {
        if(null === $this->compiledTests) {
            $this->compileTests();
        }

        return $this->compiledTests;
    }
}