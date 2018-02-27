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

class AbstractTestCaseTest extends TestCase
{
    public function testExpressions()
    {
        $obj = new SampleTestCase();

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
    }
}