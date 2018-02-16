<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase\ResponseFilters;

use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ResponseFilters\FilterTrait;

class FilterTraitTest extends TestCase
{
    use FilterTrait;

    public function testCheckExpressionCallable()
    {
        $this->assertSame(
            'somethingValue',
            $this->checkExpression('two', function($value) {
                if($value === 'two') {
                    return 'somethingValue';
                }
            })
        );
    }

    public function testInvalidExpression()
    {
        $this->assertFalse($this->checkExpression(
            '123',
            new \stdClass()
        ));
    }
}