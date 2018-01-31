<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ResponseFilters;

class FilterException extends \Exception
{
    protected $filter;

    protected $errorType;

    protected $given;

    protected $expected;

    public function __construct(FilterInterface $filter, $errorType, $given = null, $expected = null)
    {
        $this->filter = $filter;
        $this->errorType = $errorType;
        $this->given = $given;
        $this->expected = $expected;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function getErrorType()
    {
        return $this->errorType;
    }

    public function getGiven()
    {
        return $this->given;
    }

    public function getExpected()
    {
        return $this->expected;
    }
}