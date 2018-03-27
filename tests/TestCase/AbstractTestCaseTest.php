<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase;

use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCasePipeline\TestPipelineConfiguration;

class AbstractTestCaseTest extends TestCase
{
    public function testExpressions()
    {
        $configuration = new TestPipelineConfiguration([
            'tests' => [
                'namespace' => 'Sample\\',
                'path'      => 'sample',
            ],
            'variables' => [
                'sample' => 'value',
                'sample2' => [
                    'sample'  => 'value2',
                    'sample2' => 'value3',
                ],
            ],
        ]);

        $obj = new SampleTestCase($configuration);

        $this->assertSame('value', $obj->getVar('sample'));
        $this->assertSame('value2', $obj->getVar('sample2.sample'));
        $this->assertSame('value3', $obj->getVar('sample2.sample2'));
        $this->assertSame([
            'sample'  => 'value2',
            'sample2' => 'value3',
        ], $obj->getVar('sample2'));

        $equalsTo = $obj->equalsTo(20, true);
        $this->assertInstanceOf(Expression::class, $equalsTo);
        $this->assertSame(20, $equalsTo->getParam(0));
        $this->assertTrue($equalsTo->getParam(1));

        $containsString = $obj->containsString('sampleString');
        $this->assertInstanceOf(Expression::class, $containsString);
        $this->assertSame('sampleString', $containsString->getParam(0));

        $startsWith = $obj->startsWith('sampleString');
        $this->assertInstanceOf(Expression::class, $startsWith);
        $this->assertSame('sampleString', $startsWith->getParam(0));

        $endsWith = $obj->endsWith('endsWith');
        $this->assertInstanceOf(Expression::class, $endsWith);
        $this->assertSame('endsWith', $endsWith->getParam(0));

        $lessThan = $obj->lessThan(123, true);
        $this->assertInstanceOf(Expression::class, $lessThan);
        $this->assertSame(123, $lessThan->getParam(0));
        $this->assertTrue( $lessThan->getParam(1));

        $each = $obj->each($lessThan);
        $this->assertInstanceOf(Expression::class, $each);
        $this->assertSame($lessThan, $each->getParam(0));

        $each2 = $obj->each([$lessThan, $endsWith]);
        $this->assertInstanceOf(Expression::class, $each2);
        $this->assertArrayHasKey(0, $each2->getParam(0));
        $this->assertArrayHasKey(1, $each2->getParam(0));
        $this->assertSame($lessThan, $each2->getParam(0)[0]);
        $this->assertSame($endsWith, $each2->getParam(0)[1]);

        $moreThan = $obj->moreThan(123, true);
        $this->assertInstanceOf(Expression::class, $moreThan);
        $this->assertSame(123, $moreThan->getParam(0));
        $this->assertTrue( $moreThan->getParam(1));

        $beforeDate = $obj->beforeDate('2018-10-10 10:00:00');
        $this->assertInstanceOf(Expression::class, $beforeDate);
        $this->assertSame('2018-10-10 10:00:00', $beforeDate->getParam(0));

        $afterDate = $obj->afterDate('2018-10-10 10:10:10');
        $this->assertInstanceOf(Expression::class, $afterDate);
        $this->assertSame('2018-10-10 10:10:10', $afterDate->getParam(0));

        $regex = $obj->regex('/[a-z]{1,}/');
        $this->assertInstanceOf(Expression::class, $regex);
        $this->assertSame('/[a-z]{1,}/', $regex->getParam(0));
    }
}