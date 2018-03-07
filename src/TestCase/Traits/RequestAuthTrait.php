<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\Traits;

use RestControl\TestCase\Request;

trait RequestAuthTrait
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return $this
     */
    public function httpBasicAuth($username, $password)
    {
        return $this->header(
            Request::HEADER_AUTH,
            'Basic ' . base64_encode($username . ':' . $password)
        );
    }
}