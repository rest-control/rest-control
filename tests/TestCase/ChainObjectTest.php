<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase;

use Api\TestCase\ChainObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

class ChainObjectTest extends TestCase
{
    public function testInstance()
    {
        $obj = new ChainObject(
            'SampleObject',
            [
                'test' => 123,
                'test2' => 'asdd',
                'test3' => [
                    'param' => 'asdf',
                ],
            ]
        );

        $this->assertSame('SampleObject', $obj->getObjectName());
        $this->assertSame([
            'test' => 123,
            'test2' => 'asdd',
            'test3' => [
                'param' => 'asdf',
            ],
        ], $obj->getParams());
    }

    public function testNoObjectName()
    {
        $this->expectException(InvalidArgumentException::class);
        new ChainObject('');
    }
}