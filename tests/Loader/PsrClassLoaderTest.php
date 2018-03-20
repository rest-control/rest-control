<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\Loader;

use Psr\Log\InvalidArgumentException;
use RestControl\Loader\PsrClassLoader;
use PHPUnit\Framework\TestCase;

class PsrClassLoaderTest extends TestCase
{
    public function testSetInvalidNamespace()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration namespace must be a string.');

        new PsrClassLoader([]);
    }

    public function testNotExistingPathInConfiguration()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration path must be a string.');

        new PsrClassLoader([
            'namespace' => 'Sample\\Namespace',
        ]);
    }

    public function testWrongFormatPathInConfiguration()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Configuration path must be a string.');

        new PsrClassLoader([
            'namespace' => 'Sample\\Namespace\'',
            'path'      => ['wrong format'],
        ]);
    }

    public function testLoad()
    {
        $loader = new PsrClassLoader([
            'namespace' => 'RestControl\Tests\Loader\SamplePathWithTestCase\\',
            'path'         => dirname(__FILE__) . '/SamplePathWithTestsCase',
            'classSuffix'  => 'Test.php',
            'methodPrefix' => 'mySuffix'
        ]);

        $delegates = $loader->load();

        $delegatesSchema = [
            'RestControl\Tests\Loader\SamplePathWithTestCase\Rec\Rec\SampleTest' => [
                'namespace'   => 'RestControl\Tests\Loader\SamplePathWithTestCase\Rec\Rec\SampleTest',
                'method'      => 'mySuffixSampleTestRandom',
                'title'       => '',
                'description' => '',
                'tags'        => [],
            ],
            'RestControl\Tests\Loader\SamplePathWithTestCase\Rec\SampleTest' => [
                'namespace'   => 'RestControl\Tests\Loader\SamplePathWithTestCase\Rec\SampleTest',
                'method'      => 'mySuffixSampleTestSample',
                'title'       => '',
                'description' => '',
                'tags'        => [],
            ],
            'RestControl\Tests\Loader\SamplePathWithTestCase\SampleTest' => [
                'namespace'   => 'RestControl\Tests\Loader\SamplePathWithTestCase\SampleTest',
                'method'      => 'mySuffixSampleTest',
                'title'       => 'Sample testCase',
                'description' => 'Sample long description of testCase',
                'tags'        => [
                    'sample',
                    'apiv2',
                    'rest',
                ],
            ]
        ];

        foreach($delegates as $i => $delegate) {
            /** @var \RestControl\Loader\TestCaseDelegate $delegate */
            $this->assertArrayHasKey($delegate->getClassName(), $delegatesSchema);
            $this->assertSame(
                $delegatesSchema[$delegate->getClassName()],
                [
                    'namespace'   => $delegate->getClassName(),
                    'method'      => $delegate->getMethodName(),
                    'title'       => $delegate->getTitle(),
                    'description' => $delegate->getDescription(),
                    'tags'        => $delegate->getTags(),
                ]
            );
        }
    }
}