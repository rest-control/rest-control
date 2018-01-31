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
 * Class ChainObject
 * @package RestControl\TestCase
 */
class ChainObject
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $objectName;

    /**
     * ChainObject constructor.
     *
     * @param string $objectName
     * @param array  $params
     */
    public function __construct($objectName, array $params = [])
    {
        if(!is_string($objectName) || strlen($objectName) < 1) {
            throw new InvalidArgumentException('Chain object name must be a string with min 1 length.');
        }

        $this->objectName = (string) $objectName;
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $i
     * @param mixed $default
     *
     * @return mixed|null
     */
    public function getParam($i, $default = null)
    {
        if(isset($this->params[$i])) {
            return $this->params[$i];
        }

        return $default;
    }

    /**
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }
}