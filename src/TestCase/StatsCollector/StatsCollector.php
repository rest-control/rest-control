<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\StatsCollector;

use RestControl\TestCase\ResponseFilters\FilterInterface;

/**
 * Class StatsCollector
 *
 * @package RestControl\TestCase\StatsCollector
 */
class StatsCollector implements StatsCollectorInterface
{
    /**
     * @var array
     */
    protected $filterErrors = [];

    /**
     * @var int
     */
    protected $assertions = 0;

    /**
     * @param FilterInterface $filter
     * @param $errorCode
     * @param $givenValue
     * @param $expectedValue
     *
     * @return $this
     */
    public function filterError(
        FilterInterface $filter,
        $errorCode,
        $givenValue = null,
        $expectedValue = null
    ){
        $this->filterErrors []= [
            'filter'        => $filter,
            'errorCode'     => $errorCode,
            'givenValue'    => $givenValue,
            'expectedValue' => $expectedValue,
        ];

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->filterErrors) > 0;
    }

    /**
     * @return EndContextException
     */
    public function endContext()
    {
        return new EndContextException();
    }

    /**
     * @return array
     */
    public function getFilterErrors()
    {
        return $this->filterErrors;
    }


    /**
     * @param int $inc
     *
     * @return $this
     */
    public function addAssertionsCount($inc = 1)
    {
        $this->assertions += (int) $inc;

        return $this;
    }

    /**
     * @return int
     */
    public function getAssertionsCount()
    {
        return $this->assertions;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->assertions   = 0;
        $this->filterErrors = [];

        return $this;
    }
}