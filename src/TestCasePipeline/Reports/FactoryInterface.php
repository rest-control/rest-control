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

interface FactoryInterface
{
    /**
     * @param ReportInterface $report
     *
     * @return $this
     */
    public function addReport(ReportInterface $report);

    /**
     * @param string $name
     *
     * @return ReportInterface
     */
    public function get($name);
}