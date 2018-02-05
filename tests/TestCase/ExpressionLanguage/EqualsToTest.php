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
use RestControl\TestCase\ExpressionLanguage\EqualsTo;
use RestControl\TestCase\ExpressionLanguage\Expression;

class EqualsToTest extends TestCase
{
    public function testChecker()
    {
        $checker = new EqualsTo();

        $this->assertSame('equalsTo', $checker->getName());

        //check integer
        $this->assertTrue($checker->check($this->getExpression([10]), 10));
        $this->assertTrue($checker->check($this->getExpression([10]), '10'));
        $this->assertFalse($checker->check($this->getExpression([10, true]), '10'));
        $this->assertTrue($checker->check($this->getExpression([10, true]), 10));

        //check array
        $this->assertTrue($checker->check(
            $this->getExpression([[
                'sample' => 'value',
                'next'   => 'value',
            ]]),
            [
                'sample' => 'value',
                'next'   => 'value',
            ]
        ));

        $this->assertFalse($checker->check(
            $this->getExpression([[
                'sample' => 'value',
                'next'   => 'value',
            ]]),
            [
                'next'   => 'value',
            ]
        ));
    }

    /**
     * @param array $params
     *
     * @return Expression
     */
    protected function getExpression(array $params = [])
    {
        return new Expression('equalsTo', $params);
    }
}