<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase\ExpressionLanguage;

use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ExpressionLanguage\Between;
use RestControl\TestCase\ExpressionLanguage\Expression;

class BetweenTest extends TestCase
{
    public function testChecker()
    {
        $checker = new Between();

        $this->assertSame('between', $checker->getName());
        $this->assertFalse($checker->check($this->getExpression([11]), 10));
        $this->assertFalse($checker->check($this->getExpression([20]), 10));
        $this->assertTrue($checker->check($this->getExpression([null]), 10));
        $this->assertTrue($checker->check($this->getExpression([5]), 10));

        $this->assertFalse($checker->check($this->getExpression([null, 80]), 100));
        $this->assertFalse($checker->check($this->getExpression([null, 20]), 100));
        $this->assertTrue($checker->check($this->getExpression([]), 100));
        $this->assertTrue($checker->check($this->getExpression([null, 101]), 100));


        $this->assertTrue($checker->check($this->getExpression([10, 20]), 15));
        $this->assertTrue($checker->check($this->getExpression([1234, 1236]), 1234));

        $this->assertFalse($checker->check($this->getExpression([10, 20]), 21));
        $this->assertFalse($checker->check($this->getExpression([1234, 1236]), 1237));
    }

    /**
     * @param array $params
     *
     * @return Expression
     */
    protected function getExpression(array $params = [])
    {
        return new Expression('between', $params);
    }
}