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
use RestControl\Validators\DateValidatorAdapter;

class DateValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new DateValidatorAdapter();

        $this->assertTrue($validator->isValid('2000-10-10'));
        $this->assertFalse($validator->isValid('2000-10-10 10:10:10'));
        $this->assertFalse($validator->isValid('sample string'));
        $this->assertTrue($validator->isValid('2000-10-10 10:10:10', ['Y-m-d H:i:s']));
    }
}