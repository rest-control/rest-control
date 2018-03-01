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
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpUseProxy;

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