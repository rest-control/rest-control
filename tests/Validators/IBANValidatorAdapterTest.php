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
use RestControl\Validators\IBANValidatorAdapter;

class IBANValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new IBANValidatorAdapter();

        $this->assertFalse($validator->isValid('sample string'));
        $this->assertTrue($validator->isValid('DE89 3704 0044 0532 0130 00'));
        $this->assertFalse($validator->isValid('DE89 3704 0044 0532 0130 00', ['PL']));
        $this->assertTrue($validator->isValid('PL61 1090 1014 0000 0712 1981 2874', ['PL']));
    }
}