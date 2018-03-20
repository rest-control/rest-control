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

use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\ResponseFilters\ContentTypeFilter;

trait ResponseContentTypeTrait
{
    /**
     * @param callable|Expression $expression
     *
     * @return $this
     */
    public function contentType($expression)
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function contentTypeAudioAac()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['audio/aac']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXAbiword()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/x-abiword']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationOctetStream()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/octet-stream']);
    }
}