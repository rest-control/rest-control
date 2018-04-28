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
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ExpressionLanguage\OneOf;

class OneOfTest extends TestCase
{
    public function testChecker()
    {
        $checker = new OneOf();

        $this->assertTrue($checker->check($this->getExpression(
            new Expression('between', [10, 20]),
            new Expression('lessThan', [16])
        ), 15));

        $this->assertFalse($checker->check($this->getExpression(
            new Expression('between', [10, 20]),
            new Expression('lessThan', [16])
        ), 25));
    }

    /**
     * @param array ...$expressions
     *
     * @return Expression
     */
    protected function getExpression(...$expressions)
    {
        return new Expression('oneOf', $expressions);
    }
}