<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Loader;

interface LoaderInterface
{
    /**
     * Returns array of TestCaseDelegate objects.
     *
     * @return array
     */
    public function load();
}