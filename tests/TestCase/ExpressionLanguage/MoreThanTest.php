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
use Psr\Log\InvalidArgumentException;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ExpressionLanguage\GreaterThan;

class GreaterThanTest extends TestCase
{
    public function testChecker()
    {
        $checker = new GreaterThan();

        $this->assertSame('greaterThan', $checker->getName());

        $this->assertTrue($checker->check(
            $this->getExpression(5),
            10
        ));

        $this->assertFalse($checker->check(
            $this->getExpression(5),
            5
        ));

        $this->assertFalse($checker->check(
            $this->getExpression(5),
            1
        ));

        $this->assertTrue($checker->check(
            $this->getExpression(5, true),
            5
        ));
        $this->assertFalse($checker->check(
            $this->getExpression(5, true),
            4
        ));

        $this->expectException(InvalidArgumentException::class);
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
     * @param mixed $greaterThan
     * @param bool  $orEqual
     *
     * @return Expression
     */
    protected function getExpression($greaterThan, $orEqual = false)
    {
        return new Expression('greaterThan', [$greaterThan, $orEqual]);
    }
}