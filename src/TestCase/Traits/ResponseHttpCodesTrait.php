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
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpBadGateway;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpBadRequest;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpBandwidthLimitExceeded;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpConflict;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpConnectionRefused;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpConnectionTimedOut;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpContinue;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpCreated;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpExpectationFailed;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpForbidden;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpFound;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpGatewayTimeout;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpGone;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpHttpVersionNotSupported;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpImATeapot;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpInsufficientStorage;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpInternalServerError;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpLengthRequired;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpLoopDetected;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpMovedPermanently;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpMultipleChoices;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNetworkAuthenticationRequired;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNoContent;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNonAuthoritativeInformation;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNotAcceptable;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNotExtended;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNotFound;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNotImplemented;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNotModified;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpOk;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpPartialContent;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpPaymentRequired;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpPreconditionFailed;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpProxyAuthenticationRequired;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpRequestedRangeNotSatisfiable;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpRequestEntityTooLarge;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpRequestTimeout;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpRequestUriTooLong;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpResetContent;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpSeeOther;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpServiceUnavailable;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpSwitchingProtocols;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpSwitchProxy;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpTemporaryRedirect;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpTooManyRedirects;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUnauthorized;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUnavailableForLegalReasons;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUnsupportedMediaType;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUseProxy;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpVariantAlsoNegotiates;

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

    /**
     * @return $this
     */
    public function httpStatusBadRequest()
    {
        return $this->_add(HttpBadRequest::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusUnauthorized()
    {
        return $this->_add(HttpUnauthorized::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusPaymentRequired()
    {
        return $this->_add(HttpPaymentRequired::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusForbidden()
    {
        return $this->_add(HttpForbidden::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusNotFound()
    {
        return $this->_add(HttpNotFound::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusNotAcceptable()
    {
        return $this->_add(HttpNotAcceptable::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusProxyAuthenticationRequired()
    {
        return $this->_add(HttpProxyAuthenticationRequired::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusRequestTimeout()
    {
        return $this->_add(HttpRequestTimeout::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusConflict()
    {
        return $this->_add(HttpConflict::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusGone()
    {
        return $this->_add(HttpGone::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusLengthRequired()
    {
        return $this->_add(HttpLengthRequired::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusPreconditionFailed()
    {
        return $this->_add(HttpPreconditionFailed::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusRequestEntityTooLarge()
    {
        return $this->_add(HttpRequestEntityTooLarge::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusRequestUriTooLong()
    {
        return $this->_add(HttpRequestUriTooLong::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusUnsupportedMediaType()
    {
        return $this->_add(HttpUnsupportedMediaType::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusRequestedRangeNotSatisfiable()
    {
        return $this->_add(HttpRequestedRangeNotSatisfiable::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusExpectationFailed()
    {
        return $this->_add(HttpExpectationFailed::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusImATeapot()
    {
        return $this->_add(HttpImATeapot::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusUnavailableForLegalReasons()
    {
        return $this->_add(HttpUnavailableForLegalReasons::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusInternalServerError()
    {
        return $this->_add(HttpInternalServerError::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusNotImplemented()
    {
        return $this->_add(HttpNotImplemented::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusBadGateway()
    {
        return $this->_add(HttpBadGateway::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusServiceUnavailable()
    {
        return $this->_add(HttpServiceUnavailable::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusGatewayTimeout()
    {
        return $this->_add(HttpGatewayTimeout::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusHttpVersionNotSupported()
    {
        return $this->_add(HttpHttpVersionNotSupported::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusVariantAlsoNegotiates()
    {
        return $this->_add(HttpVariantAlsoNegotiates::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusInsufficientStorage()
    {
        return $this->_add(HttpInsufficientStorage::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusLoopDetected()
    {
        return $this->_add(HttpLoopDetected::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusBandwidthLimitExceeded()
    {
        return $this->_add(HttpBandwidthLimitExceeded::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusNotExtended()
    {
        return $this->_add(HttpNotExtended::FILTER_NAME, func_get_args());
    }

    /**
     * @return $this
     */
    public function httpStatusNetworkAuthenticationRequired()
    {
        return $this->_add(HttpNetworkAuthenticationRequired::FILTER_NAME, func_get_args());
    }
}