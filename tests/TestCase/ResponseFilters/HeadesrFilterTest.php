<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase\ResponseFilters;

use RestControl\TestCase\ResponseFilters\HeadersFilter;
use PHPUnit\Framework\TestCase;

class HeadesrFilterTest extends TestCase
{
    public function testName()
    {
        $this->assertSame('headers', (new HeadersFilter())->getName());
    }

    public function testValidateParams()
    {
        $filter = new HeadersFilter();

        $this->assertFalse($filter->validateParams(['sample']));
        $this->assertTrue($filter->validateParams([[]]));
    }
}