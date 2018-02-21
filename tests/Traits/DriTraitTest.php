<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Traits\Utils;

use PHPUnit\Framework\TestCase;
use RestControl\Traits\DirTrait;

class DirTraitTest extends TestCase
{
    use DirTrait;

    public function testParseDir()
    {
        $this->assertSame(
            'Sample\\Namespace\\With\\CamelCase',
            $this->parseDir('sample.namespace.with.CamelCase')
        );

        $this->assertSame(
            'Sample\\Namespace',
            $this->parseDir('sample.namespace')
        );
    }

    public function testWrongDir()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->parseDir(['wrong dir']);
    }

    public function testVirtualDirToDir()
    {
        $this->assertSame(
            implode(DIRECTORY_SEPARATOR, [
                'Test',
                'Sample',
                'Namespace',
                'CamelCase',
            ]),
            $this->virtualDirToDir('test.sample.Namespace.camelCase')
        );
        $this->assertSame(
            implode(DIRECTORY_SEPARATOR, [
                'Sample',
                'Namespace',
                'CamelCase',
            ]),
            $this->virtualDirToDir('test.sample.Namespace.camelCase', 'Test')
        );

        $this->assertSame(
            implode(DIRECTORY_SEPARATOR, [
                'Sample',
                'Namespace',
                'CamelCase',
            ]),
            $this->virtualDirToDir('test.sample.Namespace.camelCase', 'Test\\')
        );

        $this->assertSame(
            implode(DIRECTORY_SEPARATOR, [
                'Namespace',
                'CamelCase',
            ]),
            $this->virtualDirToDir('test.sample.Namespace.camelCase', 'Test\Sample\\')
        );
    }
}