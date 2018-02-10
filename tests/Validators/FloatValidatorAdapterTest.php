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
use RestControl\Validators\FloatValidatorAdapter;

class FloatValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new FloatValidatorAdapter();

        $this->assertFalse($validator->isValid(1234));
        $this->assertFalse($validator->isValid('1234.0'));
        $this->assertTrue($validator->isValid(1234.0));
        $this->assertTrue($validator->isValid(-123.0));
        $this->assertTrue($validator->isValid(-123.435345));
    }
}