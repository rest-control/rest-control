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

use RestControl\TestCase\ResponseFilters\HttpCode;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpConnectionRefused;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpConnectionTimedOut;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpContinue;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpCreated;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpFound;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpMovedPermanently;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpMultipleChoices;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNoContent;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNonAuthoritativeInformation;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNotModified;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpOk;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpPartialContent;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpResetContent;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpSeeOther;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpSwitchingProtocols;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpSwitchProxy;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpTemporaryRedirect;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpTooManyRedirects;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUseProxy;

/**
 * Trait ResponseHttpCodesTrait
 * @package RestControl\TestCase\Traits
 */
trait ResponseHttpCodesTrait
{
    /**
     * @return $this
     */
    public function httpCode($httpStatusCode)
    {
        return $this->_add(HttpCode::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusConnectionRefused()
    {
        return $this->_add(HttpConnectionRefused::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusConnectionTimeout()
    {
        return $this->_add(HttpConnectionTimedOut::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusContinue()
    {
        return $this->_add(HttpContinue::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusSwitchingProtocols()
    {
        return $this->_add(HttpSwitchingProtocols::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusOk()
    {
        return $this->_add(HttpOk::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusCreated()
    {
        return $this->_add(HttpCreated::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusNonAuthoritativeInformation()
    {
        return $this->_add(HttpNonAuthoritativeInformation::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusNoContent()
    {
        return $this->_add(HttpNoContent::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusResetContent()
    {
        return $this->_add(HttpResetContent::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusPartialContent()
    {
        return $this->_add(HttpPartialContent::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusMultipleChoices()
    {
        return $this->_add(HttpMultipleChoices::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusMovedPermanently()
    {
        return $this->_add(HttpMovedPermanently::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusFound()
    {
        return $this->_add(HttpFound::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpSeeOther()
    {
        return $this->_add(HttpSeeOther::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusNotModified()
    {
        return $this->_add(HttpNotModified::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusUseProxy()
    {
        return $this->_add(HttpUseProxy::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusSwitchProxy()
    {
        return $this->_add(HttpSwitchProxy::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusTemporaryRedirect()
    {
        return $this->_add(HttpTemporaryRedirect::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusTooManyRedirects()
    {
        return $this->_add(HttpTooManyRedirects::FILTER_NAME, func_get_args());
    }
}