<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\Loader;

use RestControl\Loader\TestCaseDelegate;
use PHPUnit\Framework\TestCase;

class TestCaseDelegateTest extends TestCase
{
    public function testGetters()
    {
        $delegate = new TestCaseDelegate(
            'sampleClass\name',
            'methodNameSample',
            'Sample title',
            'Sample long description',
            [
                'api',
                'tag',
                'sample',
            ]
        );

        $this->assertSame('sampleClass\name', $delegate->getClassName());
        $this->assertSame('methodNameSample', $delegate->getMethodName());
        $this->assertSame('Sample title', $delegate->getTitle());
        $this->assertSame('Sample long description', $delegate->getDescription());
        $this->assertSame([
            'api',
            'tag',
            'sample',
        ], $delegate->getTags());
    }
}