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

class ExpressionTest extends TestCase
{
    public function testExpressionObject()
    {
        $expression = new Expression(
            'sampleExpression',
            [10, 'sample']
        );

        $this->assertSame('sampleExpression', $expression->getName());
        $this->assertSame([
            10,
            'sample',
        ], $expression->getParams());

        $this->assertSame('sample', $expression->getParam(1));
    }
}