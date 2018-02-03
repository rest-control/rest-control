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

use Doctrine\Common\Annotations\DocParser;
use RestControl\Loader\Annotations\AnnotationInterface;
use RestControl\Loader\Annotations\TestAnnotation;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class PsrClassLoader
 *
 * @package RestControl\Loader
 */
class PsrClassLoader implements LoaderInterface
{
    /**
     * @var array
     */
    protected $annotations = [
        '\RestControl\Loader\Annotations\TestAnnotation'
    ];

    /**
     * @var array
     */
    protected $namespaces = [];

    /**
     * @var null|DocParser
     */
    protected $docParser = null;

    /**
     * PsrClassLoader constructor.
     *
     * @param array $namespaces
     */
    public function __construct(array $namespaces = [])
    {
        foreach($namespaces as $namespace => $configuration) {
            $this->addNamespace($namespace, $configuration);
        }
    }

    /**
     * @param string $namespace
     * @param array  $configuration
       <pre>
         [
            'path'          => string, //path to TestCases
            'classSuffix'   => string, //suffix for TestCase classes
            'methodPrefix'  => string, //prefix for TestCase methods
         ]
       </pre>
     */
    public function addNamespace($namespace, array $configuration)
    {
        if(!is_string($namespace)) {
            throw new \InvalidArgumentException('Namespace must be a string.');
        }

        if(!isset($configuration['path']) || !is_string($configuration['path'])) {
            throw new \InvalidArgumentException('Configuration path must be a string.');
        }

        if(!isset($configuration['classSuffix']) || !is_string($configuration['classSuffix'])) {
            $configuration['classSuffix'] = '.php';
        }

        if(!isset($configuration['methodPrefix']) || !is_string($configuration['methodPrefix'])) {
            $configuration['methodPrefix'] = 'test';
        }

        $this->namespaces [$namespace] = $configuration;
    }

    /**
     * Returns array of TestCaseDelegate objects.
     *
     * @return array
     */
    public function load()
    {
        $delegates = [];

        foreach($this->namespaces as $namespace => $configuration) {

            $finder = new Finder();
            $finder->files()
                    ->in($configuration['path'])
                    ->name('*' . $configuration['classSuffix']);

            foreach($finder as $fileInfo) {
                /** @var \Symfony\Component\Finder\SplFileInfo $fileInfo */
                $fullNamespace = $this->transformFileInfoIntoNamespace($namespace, $fileInfo);
                $pathInfo      = pathinfo($fileInfo->getFilename());

                if(!isset($pathInfo['filename'])) {
                    continue;
                }

                $reflection = new \ReflectionClass($fullNamespace . $pathInfo['filename']);

                $delegates = array_merge(
                    $delegates,
                    $this->compileTestCase($reflection, $configuration)
                );

            }
        }

        return $delegates;
    }

    /**
     * @param string      $baseNamespace
     * @param SplFileInfo $fileInfo
     *
     * @return string
     */
    protected function transformFileInfoIntoNamespace($baseNamespace, SplFileInfo $fileInfo)
    {
        $namespace  = $baseNamespace;
        $namespace .= str_replace('/', '\\', $fileInfo->getRelativePath());

        if(substr($namespace, -1) !== '\\') {
            $namespace .= '\\';
        }

        return $namespace;
    }


    /**
     * Returns array of TestCaseDelegate.
     *
     * @param \ReflectionClass $reflection
     * @param array            $namespaceConfiguration
     *
     * @return array
     */
    protected function compileTestCase(\ReflectionClass $reflection, array $namespaceConfiguration)
    {
        $docParser = $this->getDocParser();
        $delegates = [];

        foreach($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {

            if(strpos($method->getName(), $namespaceConfiguration['methodPrefix']) !== 0) {
                continue;
            }

            $annotations = $docParser->parse(
                $method->getDocComment()
            );

            $testAnnotation = null;

            foreach($annotations as $annotation) {
                if($annotation instanceof TestAnnotation) {
                    $testAnnotation = $annotation;
                    break;
                }
            }

            if(!$testAnnotation) {
                $testAnnotation = new TestAnnotation();
            }

            $tags = [];

            if(is_string($testAnnotation->tags) && strlen($testAnnotation->tags) > 0) {
                $tags = explode(' ', $testAnnotation->tags);
            }

            $delegates []= new TestCaseDelegate(
                $reflection->getName(),
                $method->getName(),
                $testAnnotation->title,
                $testAnnotation->description,
                $tags
            );
        }

        return $delegates;
    }

    /**
     * @return DocParser
     */
    protected function getDocParser()
    {
        if($this->docParser) {
            return $this->docParser;
        }

        $this->docParser = new DocParser();
        $this->docParser->setIgnoreNotImportedAnnotations(true);

        $imports = [];

        foreach($this->annotations as $annotation) {

            $obj = new $annotation;

            if(!$obj instanceof AnnotationInterface) {
                throw new \InvalidArgumentException('Annotation must be instance of \RestControl\Loader\Annotations\AnnotationInterface.');
            }

            $imports[$obj->getName()] = $annotation;
        }

        $this->docParser->setImports($imports);

        return $this->docParser;
    }
}