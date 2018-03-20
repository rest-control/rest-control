<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCasePipeline\Stages;

use Psr\Log\InvalidArgumentException;
use RestControl\TestCasePipeline\Payload;
use RestControl\TestCasePipeline\Reports\ReportInterface;

class ReportStage
{
    /**
     * @var ReportInterface
     */
    protected $report;

    /**
     * @var string
     */
    protected $reportDir;

    /**
     * ReportStage constructor.
     *
     * @param ReportInterface $report
     * @param string          $reportDir
     */
    public function __construct(ReportInterface $report, $reportDir)
    {
        if(!is_string($reportDir)) {
            throw new InvalidArgumentException('ReportDir must be an string.');
        }

        $this->report    = $report;
        $this->reportDir = $reportDir;

        $this->checkReportDir();
    }

    /**
     * @param Payload $payload
     *
     * @return Payload
     */
    public function __invoke(Payload $payload)
    {
        $this->report->report($payload, $this->reportDir);

        return $payload;
    }

    protected function checkReportDir()
    {
        $dir = dirname($this->reportDir);

        if(!is_dir($dir) || !is_writable($dir)) {
            throw new InvalidArgumentException('Dir: ' . $this->reportDir . ' must be writable.');
        }
    }
}