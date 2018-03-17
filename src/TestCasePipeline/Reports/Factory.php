<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline\Reports;

use Psr\Log\InvalidArgumentException;

class Factory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $reports = [];

    /**
     * @param ReportInterface $report
     *
     * @return $this
     */
    public function addReport(ReportInterface $report)
    {
        $this->reports []= $report;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return ReportInterface
     */
    public function get($name)
    {
        foreach($this->reports as $report) {
            if($report->getName() == $name) {
                return $report;
            }
        }

        throw new InvalidArgumentException('Cannot find ' . $name . ' report');
    }
}