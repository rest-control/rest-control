<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase;

/**
 * Class StubGenerator
 *
 * @package RestControl\Loader
 */
class StubGenerator
{
    /**
     * @param string $namespace
     * @param string $className
     * @param array  $methods
     *
     * @return string
     */
    public function create($namespace, $className, array $methods = [])
    {
        return $this->prepareClassStub($namespace, $className, $methods);
    }

    /**
     * @param string $namespace
     * @param string $className
     * @param array  $methods
     *
     * @return string
     */
    protected function prepareClassStub($namespace, $className, array $methods = [])
    {
        $class = $this->loadStub('TestCase');

        return $this->parseStubVars($class, [
            'namespace' => $namespace,
            'className' => $className,
            'methods'   => $this->prepareMethodsStubs($methods),
        ]);
    }

    /**
     * @param array $methods
     *
     * @return string
     */
    protected function prepareMethodsStubs(array $methods = [])
    {
        $stub           = $this->loadStub('TestCaseMethod');
        $methodsContent = '';

        foreach($methods as $i => $method) {
            $methodsContent .= $this->parseStubVars($stub, [
                'methodName'  => isset($method['methodName']) ? $method['methodName'] : '',
                'title'       => isset($method['title']) ? $method['title'] : '',
                'description' => isset($method['description']) ? $method['description'] : '',
                'tags'        => isset($method['tags']) ? $method['tags'] : '',
            ]);

            if(!isset($methods[$i + 1])) {
                continue;
            }

            $methodsContent .= "\n\n";
        }

        return $methodsContent;
    }

    /**
     * @param string $stubName
     *
     * @return string
     */
    protected function loadStub($stubName)
    {
        $stubPath = dirname(__FILE__) . '/Stubs/' . $stubName . '.php.stub';

        if(!file_exists($stubPath)) {
            throw new \InvalidArgumentException('Stub '.$stubPath .' does not exists.');
        }

        return (string) file_get_contents($stubPath);
    }

    /**
     * @param string $stub
     * @param array  $vars
     *
     * @return string
     */
    protected function parseStubVars($stub, array $vars = [])
    {
        foreach($vars as $varName => $varValue) {
            $stub = str_replace('``' . $varName . '``', $varValue, $stub);
        }

        return $stub;
    }
}