<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Tests\TestCase\ResponseFilters;

use PHPUnit\Framework\TestCase;
use RestControl\TestCase\ResponseFilters\HttpCodes\AbstractPredefinedHttpCode;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpAccepted;
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
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpMethodNotAllowed;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpMovedPermanently;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpMultipleChoices;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNetworkAuthenticationRequired;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNoContent;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpNonAuthoritativeInformation;
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
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUnauthorized;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUnavailableForLegalReasons;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUnsupportedMediaType;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUseProxy;
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpVariantAlsoNegotiates;

class HttpPredefinedCodesTest extends TestCase
{
    protected $filters = [
        [
            HttpContinue::class,
            100,
            'httpStatusContinue',
        ],
        [
            HttpConnectionTimedOut::class,
            110,
            'httpStatusConnectionTimeout',
        ],
        [
            HttpConnectionRefused::class,
            111,
            'httpStatusConnectionRefused',
        ],
        [
            HttpSwitchingProtocols::class,
            101,
            'httpStatusSwitchingProtocols'
        ],
        [
            HttpOk::class,
            200,
            'httpStatusOk',
        ],
        [
            HttpCreated::class,
            201,
            'httpStatusCreated',
        ],
        [
            HttpAccepted::class,
            202,
            'httpStatusAccepted',
        ],
        [
            HttpNonAuthoritativeInformation::class,
            203,
            'httpStatusNonAuthoritativeInformation',
        ],
        [
            HttpNoContent::class,
            204,
            'httpStatusNoContent',
        ],
        [
            HttpResetContent::class,
            205,
            'httpStatusResetContent',
        ],
        [
            HttpPartialContent::class,
            206,
            'httpStatusPartialContent',
        ],
        [
            HttpMultipleChoices::class,
            300,
            'httpStatusMultipleChoices',
        ],
        [
            HttpMovedPermanently::class,
            301,
            'httpStatusMovedPermanently',
        ],
        [
            HttpFound::class,
            302,
            'httpStatusFound',
        ],
        [
            HttpSeeOther::class,
            303,
            'httpSeeOther',
        ],
        [
            HttpNotModified::class,
            304,
            'httpStatusNotModified',
        ],
        [
            HttpUseProxy::class,
            305,
            'httpStatusUseProxy',

        ],
        [
            HttpSwitchProxy::class,
            306,
            'httpStatusSwitchProxy',
        ],
        [
            HttpTemporaryRedirect::class,
            307,
            'httpStatusTemporaryRedirect',
        ],
        [
            HttpBadRequest::class,
            400,
            'httpStatusBadRequest',
        ],
        [
            HttpUnauthorized::class,
            401,
            'httpStatusUnauthorized',
        ],
        [
            HttpPaymentRequired::class,
            402,
            'httpStatusPaymentRequired',
        ],
        [
            HttpForbidden::class,
            403,
            'httpStatusForbidden',
        ],
        [
            HttpNotFound::class,
            404,
            'httpStatusNotFound',
        ],
        [
            HttpMethodNotAllowed::class,
            405,
            'httpStatusMethodNotAllowed',
        ],
        [
            HttpProxyAuthenticationRequired::class,
            407,
            'httpStatusProxyAuthenticationRequired',
        ],
        [
            HttpRequestTimeout::class,
            408,
            'httpStatusRequestTimeout',
        ],
        [
            HttpConflict::class,
            409,
            'httpStatusConflict',
        ],
        [
            HttpGone::class,
            410,
            'httpStatusGone',
        ],
        [
            HttpLengthRequired::class,
            411,
            'httpStatusLengthRequired',
        ],
        [
            HttpPreconditionFailed::class,
            412,
            'httpStatusPreconditionFailed',
        ],
        [
            HttpRequestEntityTooLarge::class,
            413,
            'httpStatusRequestEntityTooLarge',
        ],
        [
            HttpRequestUriTooLong::class,
            414,
            'httpStatusRequestUriTooLong',
        ],
        [
            HttpUnsupportedMediaType::class,
            415,
            'httpStatusUnsupportedMediaType',
        ],
        [
            HttpRequestedRangeNotSatisfiable::class,
            416,
            'httpStatusRequestedRangeNotSatisfiable',
        ],
        [
            HttpExpectationFailed::class,
            417,
            'httpStatusExpectationFailed',
        ],
        [
            HttpImATeapot::class,
            418,
            'httpStatusImATeapot',
        ],
        [
            HttpUnavailableForLegalReasons::class,
            451,
            'httpStatusUnavailableForLegalReasons',
        ],
        [
            HttpInternalServerError::class,
            500,
            'httpStatusInternalServerError',
        ],
        [
            HttpNotImplemented::class,
            501,
            'httpStatusNotImplemented',
        ],
        [
            HttpBadGateway::class,
            502,
            'httpStatusBadGateway',
        ],
        [
            HttpServiceUnavailable::class,
            503,
            'httpStatusServiceUnavailable',
        ],
        [
            HttpGatewayTimeout::class,
            504,
            'httpStatusGatewayTimeout',
        ],
        [
            HttpHttpVersionNotSupported::class,
            505,
            'httpStatusHttpVersionNotSupported',
        ],
        [
            HttpVariantAlsoNegotiates::class,
            506,
            'httpStatusVariantAlsoNegotiates',
        ],
        [
            HttpInsufficientStorage::class,
            507,
            'httpStatusInsufficientStorage',
        ],
        [
            HttpLoopDetected::class,
            508,
            'httpStatusLoopDetected',
        ],
        [
            HttpBandwidthLimitExceeded::class,
            509,
            'httpStatusBandwidthLimitExceeded',
        ],
        [
            HttpNotExtended::class,
            510,
            'httpStatusNotExtended',
        ],
        [
            HttpNetworkAuthenticationRequired::class,
            511,
            'httpStatusNetworkAuthenticationRequired',
        ],
    ];

    public function testAllCodes()
    {
        foreach($this->filters as $filterConfig) {
            $filter = new $filterConfig[0];

            $this->assertInstanceOf(AbstractPredefinedHttpCode::class, $filter);
            $this->assertSame($filterConfig[1], $filter->getHttpStatusCode());
            $this->assertSame($filterConfig[2], $filter->getName());
        }
    }
}