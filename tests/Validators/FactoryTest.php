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
use Psr\Log\InvalidArgumentException;
use RestControl\Validators\Factory;

class FactoryTest extends TestCase
{
    public function testIsValidFactory()
    {
        $this->assertTrue(Factory::isValid(
            'email',
            'sample@email.com'
        ));

        //getting validator from cache
        $this->assertTrue(Factory::isValid(
            'email',
            'another@email.com'
        ));
    }

    public function testInvalidValidatorFactory()
    {
        $this->expectException(InvalidArgumentException::class);
        Factory::isValid('undefinedValidatorTest', 'sample');
    }
}