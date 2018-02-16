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
use RestControl\Validators\IPValidatorAdapter;

class IPValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new IPValidatorAdapter();

        $this->assertFalse($validator->isValid('sample string'));
        $this->assertTrue($validator->isValid('127.0.0.1'));
        $this->assertTrue($validator->isValid('2607:f0d0:1002:51::4'));
        $this->assertTrue($validator->isValid('2607:f0d0:1002:0051:0000:0000:0000:0004'));


        $this->assertFalse($validator->isValid('2607:f0d0:1002:51::4', ['ipv4']));
        $this->assertFalse($validator->isValid('2607:f0d0:1002:0051:0000:0000:0000:0004', ['ipv4']));
    }
}