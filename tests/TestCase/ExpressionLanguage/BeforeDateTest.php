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
use RestControl\TestCase\ExpressionLanguage\BeforeDate;
use RestControl\TestCase\ExpressionLanguage\Expression;

class BeforeDateTest extends TestCase
{
    public function testChecker()
    {
        $checker = new BeforeDate();

        $this->assertSame('beforeDate', $checker->getName());

        $this->assertFalse($checker->check($this->getExpression(['2018-10-10 10:00:00']), '2018-10-10 20:00:00'));
        $this->assertTrue($checker->check($this->getExpression(['2018-10-10 10:00:00']), '2017-10-10 20:00:00'));
    }

    /**
     * @param array $params
     *
     * @return Expression
     */
    protected function getExpression(array $params = [])
    {
        return new Expression('beforeDate', $params);
    }
}