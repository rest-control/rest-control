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
use Psr\Log\InvalidArgumentException;
use RestControl\Utils\AbstractResponseItem;
use RestControl\Utils\ResponseItemsCollection;

class ResponseItemsCollectionTest extends TestCase
{
    public function testInvalidItemsClass()
    {
        $this->expectException(InvalidArgumentException::class);
        new ResponseItemsCollection(\stdClass::class);
    }

    public function testInvalidAddItem()
    {
        $sampleItem = $this->getMockBuilder(AbstractResponseItem::class)
                           ->getMockForAbstractClass();

        $collection = new ResponseItemsCollection(SampleResponseItem::class);
        $this->expectException(InvalidArgumentException::class);
        $collection->addItem($sampleItem);
    }

    public function testAddItem()
    {
        $sampleItem = new SampleResponseItem();
        $collection = new ResponseItemsCollection(SampleResponseItem::class);
        $this->assertSame($collection, $collection->addItem($sampleItem, true));
        $this->assertSame([
            [
                'item'                 => $sampleItem,
                'strictRequiredValues' => true,
            ]
        ], $collection->getItems());
        $this->assertSame(SampleResponseItem::class, $collection->getItemsClass());
    }
}