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
use RestControl\Validators\ISBNValidatorAdapter;

class ISBNValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new ISBNValidatorAdapter();

        $this->assertTrue($validator->isValid('978-1-56619-909-4', ['', '-']));
        $this->assertFalse($validator->isValid('978-1-56619-909-4', ['13']));
        $this->assertFalse($validator->isValid('sample', ['13']));
    }
}