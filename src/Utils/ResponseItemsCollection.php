<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Utils;

use Psr\Log\InvalidArgumentException;

/**
 * Class ResponseItemsCollection
 *
 * @package RestControl\Utils
 */
class ResponseItemsCollection
{
    /**
     * @var \ReflectionClass
     */
    protected $itemsClass;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * ResponseItemsCollection constructor.
     *
     * @param string $itemsClass
     */
    public function __construct($itemsClass)
    {
        $this->setItemsClass($itemsClass);
    }

    /**
     * @return string
     */
    public function getItemsClass()
    {
        return $this->itemsClass->getName();
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param AbstractResponseItem $item
     * @param bool                 $strictRequiredValues
     *
     * @return $this
     */
    public function addItem(AbstractResponseItem $item, $strictRequiredValues = false)
    {
        $itemClass = $this->itemsClass->getName();

        if(!$item instanceof $itemClass) {
            throw new InvalidArgumentException('Item must be instance of ' . $this->itemsClass->getName() . '.');
        }

        $this->items []= [
            'item'                 => $item,
            'strictRequiredValues' => (bool) $strictRequiredValues,
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getItemsClassStructure()
    {
        /** @var AbstractResponseItem $instance */
        $instance = $this->itemsClass->newInstanceWithoutConstructor();

        return $instance->getStructure();
    }

    /**
     * @param string $itemsClass
     */
    private function setItemsClass($itemsClass)
    {
        $reflection = new \ReflectionClass($itemsClass);

        if(!$reflection->isSubclassOf(AbstractResponseItem::class)) {
            throw new InvalidArgumentException('ItemsClass must be instance of ' . AbstractResponseItem::class . '.');
        }

        $this->itemsClass = $reflection;
    }
}