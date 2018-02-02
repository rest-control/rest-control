<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase;

use RestControl\TestCase\Response;

class TestResponseChain extends Response
{
    /**
     * @param string $filterName
     * @param array  $params
     */
    public function sampleFilter($filterName, array $params = [])
    {
        $this->_add($filterName, $params);
    }
}