<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\Utils;

use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\Request;

class GlobalHelperTest extends TestCase
{
    public function testHelpers()
    {
        $this->assertInstanceOf(Request::class, send());
        $this->assertInstanceOf(Expression::class, equalsTo(123));
        $this->assertInstanceOf(Expression::class, containsString('asdf'));
        $this->assertInstanceOf(Expression::class, startsWith('asdf'));
        $this->assertInstanceOf(Expression::class, endsWith('asdf'));
        $this->assertInstanceOf(Expression::class, lessThan(123));
        $this->assertInstanceOf(Expression::class, moreThan(123));
        $this->assertInstanceOf(Expression::class, eachItems(equalsTo(10)));
    }
}