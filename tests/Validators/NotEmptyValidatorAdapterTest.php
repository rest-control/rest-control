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
use RestControl\Validators\NotEmptyValidatorAdapter;

class NotEmptyValidatorAdapterTest extends TestCase
{
    public function testValidator()
    {
        $validator = new NotEmptyValidatorAdapter();

        $this->assertFalse($validator->isValid(null));
        $this->assertFalse($validator->isValid([]));
        $this->assertTrue($validator->isValid(['test' => '']));
    }
}