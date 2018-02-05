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
use RestControl\TestCase\ExpressionLanguage\EndsWith;
use RestControl\TestCase\ExpressionLanguage\Expression;

class EndsWithTest extends TestCase
{
    public function testChecker()
    {
        $checker = new EndsWith();

        $this->assertSame('endsWith', $checker->getName());

        $this->assertFalse($checker->check($this->getExpression(['String']), 'StringSample'));
        $this->assertTrue($checker->check($this->getExpression(['Sample']), 'StringSample'));
    }

    /**
     * @param array $params
     *
     * @return Expression
     */
    protected function getExpression(array $params = [])
    {
        return new Expression('endsWith', $params);
    }
}