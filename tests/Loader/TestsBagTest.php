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

use Psr\Container\ContainerInterface;
use RestControl\Loader\LoaderInterface;
use RestControl\Loader\TestCaseDelegate;
use PHPUnit\Framework\TestCase;
use RestControl\Loader\TestsBag;

class TestsBagTest extends TestCase
{
    protected $testsDelegates = [];

    protected function setUp()
    {
        $this->testsDelegates = [
            new TestCaseDelegate(
                '1',
                '1',
                null,
                null,
                ['sample', 'test', '1']
            ),
            new TestCaseDelegate(
                '2',
                '2',
                null,
                null,
                ['sample', 'test', 'another', '2']
            ),
            new TestCaseDelegate(
                '3',
                '3',
                null,
                null,
                ['sample', 'test', 'another', '3']
            ),
        ];
    }

    public function testTag1()
    {
        $di = $this->getMockBuilder(ContainerInterface::class)
                   ->getMockForAbstractClass();
        $loader = $this->getMockBuilder(LoaderInterface::class)
                       ->setMethods(['load'])
                       ->getMockForAbstractClass();
        $loader->expects($this->once())
               ->method('load')
               ->willReturn($this->testsDelegates);

        $testsBag = new TestsBag($di, [$loader]);

        $tests = $testsBag->getTests('sample');
        $this->assertSame(3, count($tests));
        $this->assertSame([
            $this->testsDelegates[0],
            $this->testsDelegates[1],
            $this->testsDelegates[2],
        ], $tests);
    }

    public function testTag2()
    {
        $di = $this->getMockBuilder(ContainerInterface::class)
            ->getMockForAbstractClass();
        $loader = $this->getMockBuilder(LoaderInterface::class)
            ->setMethods(['load'])
            ->getMockForAbstractClass();
        $loader->expects($this->once())
            ->method('load')
            ->willReturn($this->testsDelegates);

        $testsBag = new TestsBag($di, [$loader]);

        $tests = $testsBag->getTests('another');
        $this->assertSame(2, count($tests));
        $this->assertSame([
            $this->testsDelegates[1],
            $this->testsDelegates[2],
        ], $tests);
    }

    public function testTag3()
    {
        $di = $this->getMockBuilder(ContainerInterface::class)
            ->getMockForAbstractClass();
        $loader = $this->getMockBuilder(LoaderInterface::class)
            ->setMethods(['load'])
            ->getMockForAbstractClass();
        $loader->expects($this->once())
            ->method('load')
            ->willReturn($this->testsDelegates);

        $testsBag = new TestsBag($di, [$loader]);

        $tests = $testsBag->getTests('3');
        $this->assertSame(1, count($tests));
        $this->assertSame([
            $this->testsDelegates[2],
        ], $tests);
    }

    public function testTag4()
    {
        $di = $this->getMockBuilder(ContainerInterface::class)
            ->getMockForAbstractClass();
        $loader = $this->getMockBuilder(LoaderInterface::class)
            ->setMethods(['load'])
            ->getMockForAbstractClass();
        $loader->expects($this->once())
            ->method('load')
            ->willReturn($this->testsDelegates);

        $testsBag = new TestsBag($di, [$loader]);

        $tests = $testsBag->getTests('another,1');
        $this->assertSame([
            $this->testsDelegates[0],
            $this->testsDelegates[1],
            $this->testsDelegates[2],
        ], $tests);
    }

    public function testTag5()
    {
        $di = $this->getMockBuilder(ContainerInterface::class)
            ->getMockForAbstractClass();
        $loader = $this->getMockBuilder(LoaderInterface::class)
            ->setMethods(['load'])
            ->getMockForAbstractClass();
        $loader->expects($this->once())
            ->method('load')
            ->willReturn($this->testsDelegates);

        $testsBag = new TestsBag($di, [$loader]);

        $tests = $testsBag->getTests('3,2');
        $this->assertSame([
            $this->testsDelegates[1],
            $this->testsDelegates[2],
        ], $tests);
    }

    public function testTag6()
    {
        $di = $this->getMockBuilder(ContainerInterface::class)
            ->getMockForAbstractClass();
        $loader = $this->getMockBuilder(LoaderInterface::class)
            ->setMethods(['load'])
            ->getMockForAbstractClass();
        $loader->expects($this->once())
            ->method('load')
            ->willReturn($this->testsDelegates);

        $testsBag = new TestsBag($di, [$loader]);

        $tests = $testsBag->getTests('another 3');
        $this->assertSame([
            $this->testsDelegates[2],
        ], $tests);
    }

    public function testTag7()
    {
        $di = $this->getMockBuilder(ContainerInterface::class)
            ->getMockForAbstractClass();
        $loader = $this->getMockBuilder(LoaderInterface::class)
            ->setMethods(['load'])
            ->getMockForAbstractClass();
        $loader->expects($this->once())
            ->method('load')
            ->willReturn($this->testsDelegates);

        $testsBag = new TestsBag($di, [$loader]);

        $tests = $testsBag->getTests('test another');
        $this->assertSame([
            $this->testsDelegates[1],
            $this->testsDelegates[2],
        ], $tests);

        $tests = $testsBag->getTests('another test');
        $this->assertSame([
            $this->testsDelegates[1],
            $this->testsDelegates[2],
        ], $tests);
    }

    public function testTag8()
    {
        $di = $this->getMockBuilder(ContainerInterface::class)
            ->getMockForAbstractClass();
        $loader = $this->getMockBuilder(LoaderInterface::class)
            ->setMethods(['load'])
            ->getMockForAbstractClass();
        $loader->expects($this->once())
            ->method('load')
            ->willReturn($this->testsDelegates);

        $testsBag = new TestsBag($di, [$loader]);

        $tests = $testsBag->getTests('test,another 2');
        $this->assertCount(1, $tests);
        $this->assertSame([
            $this->testsDelegates[1],
        ], $tests);
    }
}