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
use RestControl\TestCase\ExpressionLanguage\LessThan;

class LessThanTest extends TestCase
{
    public function testChecker()
    {
        $checker = new LessThan();

        $this->assertSame('lessThan', $checker->getName());

        $this->assertTrue($checker->check(
            $this->getExpression(5),
            4
        ));

        $this->assertFalse($checker->check(
            $this->getExpression(5),
            5
        ));

        $this->assertFalse($checker->check(
            $this->getExpression(5),
            10
        ));

        $this->assertTrue($checker->check(
            $this->getExpression(5, true),
            5
        ));
        $this->assertFalse($checker->check(
            $this->getExpression(5, true),
            6
        ));

        $this->assertTrue($checker->check(
            $this->getExpression(10, true),
            [
                10,
                1,
                10,
                2,
                5,
                6,
                7
            ]
        ));

        $this->assertFalse($checker->check(
            $this->getExpression(10, true),
            [
                10,
                1,
                10,
                2,
                5,
                6,
                22
            ]
        ));
    }

    /**
     * @param mixed $lessThan
     * @param bool  $orEqual
     *
     * @return Expression
     */
    protected function getExpression($lessThan, $orEqual = false)
    {
        return new Expression('lessThan', [$lessThan, $orEqual]);
    }
}