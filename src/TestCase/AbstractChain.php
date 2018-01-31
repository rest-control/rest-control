<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase;

use Psr\Log\InvalidArgumentException;

/**
 * Class AbstractChain
 * @package RestControl\TestCase
 */
abstract class AbstractChain
{
    /**
     * @var array
     */
    protected $chain = [];

    /**
     * @return array
     */
    public function _getChain()
    {
        return $this->chain;
    }

    /**
     * @return int
     */
    public function _getChainLength()
    {
        return count($this->chain);
    }

    /**
     * @param $objectName
     *
     * @return array
     */
    public function _getChainObjects($objectName)
    {
        if(!is_string($objectName)) {
            throw new InvalidArgumentException('Object name must be a string.');
        }

        $objects = [];

        foreach($this->chain as $chainObject)
        {
            if($objectName === $chainObject->getObjectName()) {
                $objects []= $chainObject;
            }
        }

        return $objects;
    }

    /**
     * @param string $objectName
     *
     * @return null|ChainObject
     */
    public function _getChainObject($objectName)
    {
        $chain = $this->_getChainObjects($objectName);

        if(empty($chain)) {
           return null;
        }

        return array_shift($chain);
    }

    /**
     * @param string $objectName
     * @param array  $params
     *
     * @return $this
     */
    protected function _add($objectName, array $params = [])
    {
        $this->chain []= new ChainObject($objectName, $params);

        return $this;
    }

    /**
     * @param string $objectName
     *
     * @return $this
     */
    protected function remove($objectName)
    {
        if(!is_string($objectName)) {
            throw new InvalidArgumentException('Object name must be a string.');
        }

        foreach($this->chain as $id => $chain) {
            /** @var ChainObject $chain */
            if($objectName === $chain->getObjectName()) {
                unset($this->chain[$id]);
            }
        }

        return $this;
    }
}