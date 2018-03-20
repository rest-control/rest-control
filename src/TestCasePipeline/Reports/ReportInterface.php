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

use RestControl\TestCasePipeline\Payload;

interface ReportInterface
{
    /**
     * @param Payload $payload
     * @param string  $reportDir
     */
    public function report(Payload $payload, $reportDir);

    /**
     * @return string
     */
    public function getName();
}