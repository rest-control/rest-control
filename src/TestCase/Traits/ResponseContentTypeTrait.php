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

    /**
     * @return $this
     */
    public function contentTypeVideoXMsvideo()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['video/x-msvideo']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndAmazonEbook()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/vnd.amazon.ebook']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXBzip()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/x-bzip']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXBzip2()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/x-bzip2']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationXCsh()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/x-csh']);
    }

    /**
     * @return $this
     */
    public function contentTypeTextCss()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['text/css']);
    }

    /**
     * @return $this
     */
    public function contentTypeTextCsv()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['text/csv']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationMsword()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/msword']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndOpenxmlformatsOfficedocumentWordprocessingmlDocument()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationVndMsFontobject()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/vnd.ms-fontobject']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationEpubZip()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/epub+zip']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationEcmascript()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/ecmascript']);
    }

    /**
     * @return $this
     */
    public function contentTypeImageGif()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['image/gif']);
    }

    /**
     * @return $this
     */
    public function contentTypeTextHtml()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['text/html']);
    }

    /**
     * @return $this
     */
    public function contentTypeImageXIcon()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['image/x-icon']);
    }

    /**
     * @return $this
     */
    public function contentTypeTextCalendar()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['text/calendar']);
    }

    /**
     * @return $this
     */
    public function contentTypeApplicationJavaArchive()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['application/java-archive']);
    }

    /**
     * @return $this
     */
    public function contentTypeImageJpeg()
    {
        return $this->_add(ContentTypeFilter::FILTER_NAME, ['image/jpeg']);
    }
}