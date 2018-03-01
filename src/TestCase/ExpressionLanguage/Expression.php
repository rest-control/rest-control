<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ExpressionLanguage;

class Expression
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $params;

    /**
     * Expression constructor.
     *
     * @param string $name
     * @param array  $params
     */
    public function __construct($name, array $params = [])
    {
        $this->name   = (string) $name;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param int  $i
     * @param null $default
     *
     * @return mixed|null
     */
    public function getParam($i, $default = null)
    {
        if(!isset($this->params[$i])) {
            return $default;
        }

        return $this->params[$i];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name . '(' . implode(', ', $this->params) . ');';
    }
}