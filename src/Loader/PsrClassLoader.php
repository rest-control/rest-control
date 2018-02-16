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
use Psr\Log\InvalidArgumentException;
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
    protected $namespaceConfiguration = [];

    /**
     * @var null|DocParser
     */
    protected $docParser = null;

    /**
     * PsrClassLoader constructor.
     *
     * @param array $namespaceConfiguration
     */
    public function __construct(array $namespaceConfiguration)
    {
        $this->setNamespace($namespaceConfiguration);
    }

    /**
     * @param array $configuration
       <pre>
         [
            'namespace'     => string, //classes namespace
            'path'          => string, //path to TestCases
            'classSuffix'   => string, //suffix for TestCase classes
            'methodPrefix'  => string, //prefix for TestCase methods
         ]
       </pre>
     */
    protected function setNamespace($configuration)
    {
        if(!isset($configuration['namespace']) || !is_string($configuration['namespace'])) {
            throw new InvalidArgumentException('Configuration namespace must be a string.');
        }

        if(!isset($configuration['path']) || !is_string($configuration['path'])) {
            throw new InvalidArgumentException('Configuration path must be a string.');
        }

        if(!isset($configuration['classSuffix']) || !is_string($configuration['classSuffix'])) {
            $configuration['classSuffix'] = '.php';
        }

        if(!isset($configuration['methodPrefix']) || !is_string($configuration['methodPrefix'])) {
            $configuration['methodPrefix'] = 'test';
        }

        $this->namespaceConfiguration = $configuration;
    }

    /**
     * Returns array of TestCaseDelegate objects.
     *
     * @return array
     */
    public function load()
    {
        $delegates = [];

        $finder = new Finder();
        $finder->files()
            ->in($this->namespaceConfiguration['path'])
            ->name('*' . $this->namespaceConfiguration['classSuffix']);

        foreach($finder as $fileInfo) {
            /** @var \Symfony\Component\Finder\SplFileInfo $fileInfo */
            $fullNamespace = $this->transformFileInfoIntoNamespace(
                $this->namespaceConfiguration['namespace'],
                $fileInfo
            );

            $pathInfo = pathinfo($fileInfo->getFilename());

            if(!isset($pathInfo['filename'])) {
                continue;
            }

            $reflection = new \ReflectionClass($fullNamespace . $pathInfo['filename']);

            $delegates = array_merge(
                $delegates,
                $this->compileTestCase($reflection)
            );

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
     *
     * @return array
     */
    protected function compileTestCase(\ReflectionClass $reflection)
    {
        $docParser = $this->getDocParser();
        $delegates = [];

        foreach($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {

            if(strpos($method->getName(), $this->namespaceConfiguration['methodPrefix']) !== 0) {
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