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

class JsonReport implements ReportInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'json';
    }

    /**
     * @param Payload $payload
     * @param string  $reportDir
     */
    public function report(Payload $payload, $reportDir)
    {
        $report = [
            'tags'   => $payload->getTestsTag(),
            'suites' => $payload->getTestsSuiteObjects()
        ];

        file_put_contents($reportDir, json_encode($report));
    }
}