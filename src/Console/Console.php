<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Console;

use Composer\Autoload\ClassLoader;
use League\Container\Container;
use League\Container\ContainerInterface;
use League\Container\ReflectionContainer;
use RestControl\Console\Commands\CreateTestCaseCommand;
use RestControl\Console\Commands\RunTestsCommand;
use Symfony\Component\Console\Application;

/**
 * Class Console
 *
 * @package RestControl\Console
 */
class Console
{
    /**
     * @var null|Application
     */
    protected $app;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $commands = [
        RunTestsCommand::class,
        CreateTestCaseCommand::class,
    ];

    /**
     * Console constructor.
     *
     * @param ClassLoader $classLoader
     */
    public function __construct(
        ClassLoader $classLoader
    ){
        $this->prepareContainer($classLoader);
    }

    /**
     * Run console application.
     *
     * @return int
     */
    public function run()
    {
        $this->bootstrap()->run();

        return 0;
    }

    /**
     * @return Application
     */
    protected function bootstrap()
    {
        if($this->app) {
            return $this->app;
        }

        $this->app = new Application();

        foreach($this->commands as $command) {
            $this->addCommand($command);
        }

        return $this->app;
    }

    /**
     * @param ClassLoader $classLoader
     */
    protected function prepareContainer(ClassLoader $classLoader)
    {
        $this->container = new Container();
        $this->container->delegate(new ReflectionContainer());
        $this->container->share(ClassLoader::class, $classLoader);
    }

    /**
     * @param string $class
     */
    protected function addCommand($class)
    {
        $this->app->add(
            $this->container->get($class)
        );
    }
}