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
 * Interface StatsCollectorInterface
 *
 * @package RestControl\TestCase\StatsCollector
 */
interface StatsCollectorInterface
{

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
        $givenValue,
        $expectedValue = null
    );

    /**
     * @return EndContextException
     */
    public function endContext();

    /**
     * @param int $inc
     *
     * @return $this
     */
    public function addAssertionsCount($inc = 1);

    /**
     * @return int
     */
    public function getAssertionsCount();

    /**
     * @return $this
     */
    public function reset();

    /**
     * @return bool
     */
    public function hasErrors();

    /**
     * @return array
     */
    public function getFilterErrors();
}