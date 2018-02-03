<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\Loader\SamplePathWithTestCase\Rec;

use RestControl\TestCase\AbstractTestCase;

class SampleTest extends AbstractTestCase
{
    public function mySuffixSampleTestSample()
    {
        return $this->send();
    }
}