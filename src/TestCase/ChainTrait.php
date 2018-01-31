<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase;

use Psr\Log\InvalidArgumentException;

/**
 * Trait ChainTrait
 * @package RestControl\TestCase
 */
trait ChainTrait
{
    /**
     * @param AbstractChain $chain
     *
     * @return AbstractChain|Request
     */
    public function __getRequestChain(AbstractChain $chain)
    {
        if($chain instanceof Request) {
            return $chain;
        } else if($chain instanceof Response) {
            return $chain->expectedRequest();
        }

        throw new InvalidArgumentException('Unsupported chain type');
    }
    /**
     * @param AbstractChain $chain
     *
     * @return AbstractChain|Response
     */
    public function __getResponseChain(AbstractChain $chain)
    {
        if($chain instanceof Response) {
            return $chain;
        } else if($chain instanceof Request) {
            return $chain->expectedResponse();
        }

        throw new InvalidArgumentException('Unsupported chain type');
    }
}