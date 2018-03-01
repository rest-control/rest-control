<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Loader;

class TestCaseDelegate
{
    /**
     * @var null|string
     */
    protected $className = null;

    /**
     * @var null|string
     */
    protected $methodName = null;

    /**
     * @var null|string
     */
    protected $title = null;

    /**
     * @varn null|string
     */
    protected $description = null;

    /**
     * @var array
     */
    protected $tags = [];

    /**
     * TestCaseRunner constructor.
     *
     * @param string      $className
     * @param string      $methodName
     * @param null|string $title
     * @param null|string $description
     * @param array       $tags
     */
    public function __construct(
        $className,
        $methodName,
        $title = null,
        $description = null,
        array $tags = []
    ){
        $this->className   = (string) $className;
        $this->methodName  = (string) $methodName;
        $this->title       = (string) $title;
        $this->description = (string) $description;
        $this->tags        = $tags;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return null|string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @return null|string
     */
    public function getClassName()
    {
        return $this->className;
    }
}