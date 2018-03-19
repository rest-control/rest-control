<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ResponseFilters\ContentTypes;

class ApplicationXAbiWord extends AbstractContentType
{
    const FILTER_NAME = 'contentTypeApplicationXAbiWord';
    const HTTP_CONTENT_TYPES  = ['application/x-abiword'];
}