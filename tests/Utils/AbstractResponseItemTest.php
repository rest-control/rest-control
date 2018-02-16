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

class AbstractResponseItemTest extends TestCase
{
    public function testGetters()
    {
        $item = new SampleResponseItem([
            'id'       => 123,
            'name'     => 'Sample name',
            'password' => 'samplePassword',
        ]);

        $this->assertSame([
            'id',
            'name',
            'password',
        ], array_keys($item->getStructure()));

        $this->assertSame([
            'id'       => 123,
            'name'     => 'Sample name',
            'password' => 'samplePassword',
        ], $item->getRequiredValues());
    }
}
