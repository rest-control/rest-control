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
use RestControl\Console\Utils\ConsoleTestCasePipelineListener;
use RestControl\TestCasePipeline\TestCasePipeline;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunTestsCommand extends Command
{
    use HelpersTrait;

    /**
     * @var ClassLoader
     */
    protected $classLoader;

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
             ->setDescription('Run all given test cases')
             ->addOption(
                 'tags',
                 null,
                 InputOption::VALUE_REQUIRED,
                 'Schema: "sample,tags" or "sample tags" or "sample,tags another"',
                 ''
             );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $pipeline = new TestCasePipeline(
            $this->classLoader,
            $this->resolveConfiguration()
        );

        $this->addConsolePipelineListener($output, $pipeline);
        
        $pipeline->process(
            $input->getOption('tags')
        );

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
        $listener = new ConsoleTestCasePipelineListener(
            $output,
            $this->getHelper('formatter')
        );

        $pipeline->addSubscriber($listener);
    }
}