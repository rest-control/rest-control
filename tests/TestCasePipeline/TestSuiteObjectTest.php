<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCasePipeline;

use RestControl\Loader\TestCaseDelegate;
use RestControl\TestCasePipeline\TestObject;
use PHPUnit\Framework\TestCase;
use RestControl\TestCasePipeline\TestSuiteObject;

class TestSuiteObjectTest extends TestCase
{
    public function testGetters()
    {
        $delegate = new TestCaseDelegate(
            'sample',
            'sample2'
        );

        $testObject  = new TestObject($delegate);
        $suiteObject = new \stdClass();
        $suite       = new TestSuiteObject($suiteObject);

        $this->assertEmpty($suite->getTestsObjects());

        $suite->addTestObject($testObject);

        $this->assertSame($testObject, $suite->getTestsObjects()[0]);
        $this->assertSame($suiteObject, $suite->getSuite());
    }
}