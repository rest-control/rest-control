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

use RestControl\TestCase\StatsCollector\StatsCollector;
use RestControl\TestCase\StatsCollector\StatsCollectorInterface;
use JsonSerializable;

abstract class AbstractFilter implements JsonSerializable
{
    use FilterTrait;

    const FILTER_NAME = '';

    /**
     * @var StatsCollectorInterface|null
     */
    protected $statsCollector = null;

    /**
     * @return string
     */
    public function getName()
    {
        return (string) $this::FILTER_NAME;
    }

    /**
     * @param StatsCollectorInterface|null $statsCollector
     */
    public function setStatsCollection(StatsCollectorInterface $statsCollector = null)
    {
        $this->statsCollector = $statsCollector;
    }

    /**
     * @return StatsCollectorInterface|null
     */
    public function getStatsCollector()
    {
        if($this->statsCollector) {
           return $this->statsCollector;
        }

        $this->statsCollector = new StatsCollector();

        return $this->statsCollector;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'name'  => $this->getName(),
            'class' => get_class($this),
        ];
    }
}