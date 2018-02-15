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

use Psr\Log\InvalidArgumentException;
use RestControl\TestCase\StubGenerator;
use RestControl\Utils\DirTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class CreateTestCaseCommand
 *
 * @package RestControl\Console\Commands
 */
class CreateTestCaseCommand extends Command
{
    use HelpersTrait;
    use DirTrait;

    /**
     * Configure command.
     */
    protected function configure()
    {
        $this->setName('create:test')
             ->setDescription('Create test case in given namespace.')
             ->addArgument(
                 'path',
                 InputArgument::REQUIRED,
                 'Namespace in camelCase and dot notation, eq. sample.Namespace.camelCase.sample'
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
        $pathInfo = $this->parsePath($input->getArgument('path'));

        $this->generateStub($input, $output, $pathInfo);

        $output->writeln('Done.');

        return 0;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $pathInfo
     */
    protected function generateStub(InputInterface $input, OutputInterface $output, array $pathInfo)
    {
        $stubGenerator = new StubGenerator();
        $namespace     = $this->parseDir($pathInfo['dir']);
        $configuration = $this->resolveConfiguration()
                              ->getTestsNamespace();

        if(!$configuration) {
            throw new InvalidArgumentException('Invalid namespace '.$namespace);
        }

        $methods = $this->askForMethods($input, $output, $configuration['methodPrefix']);

        $classStub = $stubGenerator->create(
            $namespace,
            $pathInfo['className'],
            $methods
        );

        $dirToNewClass  = $configuration['path'];
        $dirToNewClass .= $this->virtualDirToDir($pathInfo['dir'], $configuration['namespace']);

        if(!is_dir($dirToNewClass)) {
            mkdir($dirToNewClass, 0777, true);
        }

        if(!is_readable($dirToNewClass)) {
            throw new InvalidArgumentException('Path '.$dirToNewClass . ' is not readable.');
        }

        if(!file_put_contents(
            $dirToNewClass
            . DIRECTORY_SEPARATOR
            . $pathInfo['className']
            . $configuration['classSuffix'],
            $classStub
        )) {
            throw new InvalidArgumentException('Cannot put contents in '. $dirToNewClass . '.');
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param string          $methodPrefix
     * @param array           $methods
     *
     * @return array
     */
    protected function askForMethods(
        InputInterface $input,
        OutputInterface $output,
        $methodPrefix,
        array $methods = []
    ){
        $question = new Question('---- [OPTIONAL] Please provide name of class method(enter for skip): ', false);
        $helper   = $this->getHelper('question');

        $value = $helper->ask($input, $output, $question);

        if(!$value) {
            return $methods;
        }

        $methodName = str_replace(' ', '', $value);

        $titleQuestion       = new Question('[OPTIONAL] Please provide title of class method: ', '');
        $descriptionQuestion = new Question('[OPTIONAL] Please provide description of class method: ', '');
        $tagsQuestion        = new Question('[OPTIONAL] Please provide tags of class method(separate with a space): ', '');

        $title       = $helper->ask($input, $output, $titleQuestion);
        $description = $helper->ask($input, $output, $descriptionQuestion);
        $tags        = $helper->ask($input, $output, $tagsQuestion);

        $methods []= [
            'title'       => $title,
            'description' => $description,
            'tags'        => $tags,
            'methodName'  => $methodPrefix . ucfirst($methodName),
        ];

        return $this->askForMethods($input, $output, $methodPrefix, $methods);
    }

    /**
     * @param string $path
     *
     * @return array
     */
    protected function parsePath($path)
    {
        $parts = explode('.', $path);

        return [
            'className' => ucfirst(array_pop($parts)),
            'dir'       => implode('.', $parts),
        ];
    }
}