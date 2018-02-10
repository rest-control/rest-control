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
use RestControl\Validators\LengthValidatorAdapter;

class LengthValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new LengthValidatorAdapter();

        $this->assertTrue($validator->isValid('asd123', [6,6]));
        $this->assertFalse($validator->isValid('asd123zxc', [6,6]));
        $this->assertTrue($validator->isValid('aa', [2,6]));
    }
}