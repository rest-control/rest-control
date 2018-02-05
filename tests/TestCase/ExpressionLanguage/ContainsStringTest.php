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
use RestControl\TestCase\ExpressionLanguage\ContainsString;
use RestControl\TestCase\ExpressionLanguage\Expression;

class ContainsStringTest extends TestCase
{
    public function testChecker()
    {
        $checker = new ContainsString();

        $this->assertSame('containsString', $checker->getName());

        $this->assertTrue($checker->check($this->getExpression(['String']), 'sampleString'));
        $this->assertTrue($checker->check($this->getExpression(['sample']), 'sampleString'));
        $this->assertFalse($checker->check($this->getExpression(['sampleStringError']), 'sampleString'));
    }

    /**
     * @param array $params
     *
     * @return Expression
     */
    protected function getExpression(array $params = [])
    {
        return new Expression('containsString', $params);
    }
}