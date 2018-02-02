<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Console\Commands;

use Composer\Autoload\ClassLoader;
use Psr\Log\InvalidArgumentException;
use RestControl\Console\Utils\ConsoleTestCasePipelineListener;
use RestControl\TestCasePipeline\TestCasePipeline;
use RestControl\TestCasePipeline\TestPipelineConfiguration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunTestsCommand
 *
 * @package RestControl\Console\Commands
 */
class RunTestsCommand extends Command
{
    /**
     * @var ClassLoader
     */
    protected $classLoader;

    /**
     * @var TestPipelineConfiguration
     */
    protected $configuration;

    /**
     * RunTestsCommand constructor.
     *
     * @param ClassLoader $classLoader
     */
    public function __construct(
        ClassLoader $classLoader
    ){
        parent::__construct();
        $this->classLoader = $classLoader;
    }

    /**
     * Configure command.
     */
    protected function configure()
    {
        $this->setName('run')
             ->setDescription('Run all given test cases');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->resolveConfiguration();

        $pipeline = new TestCasePipeline(
            $this->classLoader,
            $this->configuration
        );

        $this->addConsolePipelineListener($output, $pipeline);
        
        $pipeline->process();

        return 0;
    }

    /**
     * @param OutputInterface  $output
     * @param TestCasePipeline $pipeline
     */
    protected function addConsolePipelineListener(
        OutputInterface $output,
        TestCasePipeline $pipeline
    ){
        $listener = new ConsoleTestCasePipelineListener($output);
        $pipeline->addSubscriber($listener);
    }

    /**
     * Prepare pipeline configuration object.
     */
    protected function resolveConfiguration()
    {
        $configurationPath = getcwd() . DIRECTORY_SEPARATOR . 'configuration.php';

        if(!is_string($configurationPath)) {
            throw new InvalidArgumentException('Configuration path must be a string.');
        }

        if(!file_exists($configurationPath)) {
            $this->configuration = new TestPipelineConfiguration([]);

            return;
        }

        if(!is_readable($configurationPath)) {
            throw new InvalidArgumentException('Configuration file['.$configurationPath.'] does not have read permission.');
        }

        $config = require $configurationPath;

        if(!is_array($config)) {
            throw new InvalidArgumentException('Configuration file wrong format.');
        }

        $this->configuration = new TestPipelineConfiguration($config);
    }
}