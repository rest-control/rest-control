<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\Loader\SamplePathWithTestCase;

use RestControl\TestCase\AbstractTestCase;

class SampleTest extends AbstractTestCase
{
    /**
     * @test(
     *     title="Sample testCase",
     *     description="Sample long description of testCase",
     *     tags="sample apiv2 rest"
     * )
     */
    public function mySuffixSampleTest()
    {
        return $this->send();
    }
}