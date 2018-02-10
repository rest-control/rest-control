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
use RestControl\Validators\IntValidatorAdapter;

class IntValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new IntValidatorAdapter();

        $this->assertFalse($validator->isValid('1234'));
        $this->assertFalse($validator->isValid('asdd'));
        $this->assertTrue($validator->isValid(1234));
    }
}