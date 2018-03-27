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
use RestControl\TestCase\ExpressionLanguage\Regex;

class RegexTest extends TestCase
{
    public function testChecker()
    {
        $checker = new Regex();

        $this->assertSame('regex', $checker->getName());
        $this->assertTrue($checker->check($this->getExpression(['/[a-z]{3}/']), 'asd'));
        $this->assertFalse($checker->check($this->getExpression(['/[a-z]{3}/']), 'as0'));
    }

    /**
     * @param array $params
     *
     * @return Expression
     */
    protected function getExpression(array $params = [])
    {
        return new Expression('regex', $params);
    }
}