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
use RestControl\Utils\Arr;

class ArrTest extends TestCase
{
    public function testContainsInArray()
    {
        $this->assertTrue(Arr::containsIn(
            [
                'next'   => 'value',
                'sample' => 'value',
            ],
            [
                'sample' => 'value',
                'next'   => 'value',
            ]
        ));

        $this->assertTrue(Arr::containsIn(
            [
                'next'   => 'value',
                'sample' => 'value',
            ],
            [
                'next'   => 'value',
                'sample' => 'value',
                'test'   => 'asdd',
            ],
            false
        ));

        $this->assertTrue(Arr::containsIn(
            [
                'next'   => 'value',
                'sample' => [
                    'next' => 'value',
                    'sample' => 'value',
                ],
            ],
            [
                'next'   => 'value',
                'sample' => [
                    'sample' => 'value',
                    'next'   => 'value',
                ],
            ]
        ));

        $this->assertFalse(Arr::containsIn(
            [
                'next'   => 'value',
                'sample' => [
                    'next' => 'value',
                    'sample' => 'value',
                    'additional' => 'index',
                ],
            ],
            [
                'next'   => 'value',
                'sample' => [
                    'sample' => 'value',
                    'next'   => 'value',
                ],
            ]
        ));

        $this->assertFalse(Arr::containsIn(
            [
                'next'   => 'value',
                'sample' => [
                    'next' => 'value',
                    'sample' => 'value',
                ],
            ],
            [
                'next'   => 'value',
                'sample' => [
                    'sample' => 'value',
                    'next'   => 'value',
                    'additional' => 'index',
                ],
            ]
        ));

        $this->assertTrue(Arr::containsIn([
            'sample'    => 'value',
        ], [
            'sample' => new Expression('sample', ['lue']),
        ], false, function($leftValue, $rightValue) {
            return $rightValue instanceof Expression;
        }));
    }
}