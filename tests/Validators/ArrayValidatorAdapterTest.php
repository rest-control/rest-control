<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\Utils;

use PHPUnit\Framework\TestCase;
use RestControl\Validators\ArrayValidatorAdapter;

class ArrayValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new ArrayValidatorAdapter();

        $this->assertFalse($validator->isValid('1234'));
        $this->assertFalse($validator->isValid(new \stdClass()));
        $this->assertTrue($validator->isValid([]));
    }
}