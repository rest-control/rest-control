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

use RestControl\Loader\Annotations\TestAnnotation;
use RestControl\TestCase\AbstractTestCase;
use RestControl\Loader\Annotations\AnnotationInterface;
use Composer\Autoload\ClassLoader;
use Doctrine\Common\Annotations\DocParser;
use Psr\Log\InvalidArgumentException;

/**
 * Class ClassMapLoader
 *
 * @package RestControl\Loader
 */
class ComposerClassMapLoader implements LoaderInterface
{
    /**
     * @var array
     */
    protected $annotations = [
        '\RestControl\Loader\Annotations\TestAnnotation'
    ];

    /**
     * @var ClassLoader
     */
    protected $classLoader;

    /**
     * @var array
     */
    protected $namespaces = [];

    /**
     * @var null|string
     */
    protected $classesSuffix = null;

    /**
     * @var null|DocParser
     */
    protected $docParser = null;

    /**
     * ComposerClassMapLoader constructor.
     *
     * @param ClassLoader $classLoader
     * @param array       $namespaces
     */
    public function __construct(
        ClassLoader $classLoader,
        array $namespaces = []
    ){
        $this->classLoader = $classLoader;
        $this->namespaces  = $namespaces;
    }

    /**
     * @param string $namespace
     */
    public function addNamespace($namespace)
    {
        if(!is_string($namespace)) {
            throw new \InvalidArgumentException('namespace must be a string');
        }

        $this->namespaces []= $namespace;
    }

    /**
     * @param string|null $suffix
     */
    public function setClassesSuffix($suffix = null)
    {
        if($suffix !== null && !is_string($suffix)) {
            throw new \InvalidArgumentException('suffix must be a string');
        }

        $this->classesSuffix = $suffix;
    }

    /**
     * Returns array of TestCaseDelegate objects.
     *
     * @return array
     */
    public function load()
    {
        $delegates  = [];
        $classMap = $this->classLoader->getClassMap();

        foreach($this->namespaces as $namespace) {
            foreach($classMap as $class => $file) {
                if(strpos($class, $namespace) === 0){

                    $suffixPos = strlen($class) - strlen($this->classesSuffix);

                    if($this->classesSuffix && strpos($class, $this->classesSuffix) !== $suffixPos){
                        continue;
                    }

                    $reflection = new \ReflectionClass($class);

                    if(!$reflection->isSubclassOf(AbstractTestCase::class)) {
                        throw new \InvalidArgumentException($reflection->getName() . ' must be instance of AbstractTestCase');
                    }

                    $delegates = array_merge($delegates, $this->compileTestCase($reflection));

                }
            }
        }

        return $delegates;
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

            if(strpos($method->getName(), 'test') !== 0) {
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
                throw new InvalidArgumentException('Annotation must be instance of \RestControl\Loader\Annotations\AnnotationInterface.');
            }

            $imports[$obj->getName()] = $annotation;
        }

        $this->docParser->setImports($imports);

        return $this->docParser;
    }
}