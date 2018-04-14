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

use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ResponseFilters\ContentTypeFilter;
use RestControl\TestCase\ResponseFilters\FilterInterface;
use Psr\Log\InvalidArgumentException;
use RestControl\TestCase\ResponseFilters\HasItemFilter;
use RestControl\TestCase\ResponseFilters\HasItemsFilter;
use RestControl\TestCase\ResponseFilters\HeaderFilter;
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
use RestControl\TestCase\ResponseFilters\HttpCodes\HttpMethodNotAllowed;
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
use RestControl\TestCase\ResponseFilters\JsonFilter;
use RestControl\TestCase\ResponseFilters\JsonPathFilter;
use RestControl\TestCase\ResponseFilters\SizeFilter;
use RestControl\TestCase\StatsCollector\StatsCollector;
use RestControl\TestCase\StatsCollector\StatsCollectorInterface;

class ResponseFiltersBag
{
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * ResponseFiltersBag constructor.
     *
     * @param array $filters
     */
    public function __construct(array $filters = [])
    {
        $this->addFilters([
            new JsonFilter(),
            new HeaderFilter(),
            new JsonPathFilter(),
            new HeaderFilter(),
            new HasItemFilter(),
            new HasItemsFilter(),
            new HttpCode(),
            new HttpConnectionRefused(),
            new HttpConnectionTimedOut(),
            new HttpContinue(),
            new HttpSwitchingProtocols(),
            new HttpOk(),
            new HttpCreated(),
            new HttpNonAuthoritativeInformation(),
            new HttpNoContent(),
            new HttpResetContent(),
            new HttpPartialContent(),
            new HttpMultipleChoices(),
            new HttpMovedPermanently(),
            new HttpFound(),
            new HttpSeeOther(),
            new HttpNotModified(),
            new HttpUseProxy(),
            new HttpSwitchProxy(),
            new HttpTemporaryRedirect(),
            new HttpTooManyRedirects(),
            new HttpBadRequest(),
            new HttpUnauthorized(),
            new HttpPaymentRequired(),
            new HttpForbidden(),
            new HttpNotFound(),
            new HttpMethodNotAllowed(),
            new HttpNotAcceptable(),
            new HttpProxyAuthenticationRequired(),
            new HttpRequestTimeout(),
            new HttpConflict(),
            new HttpGone(),
            new HttpLengthRequired(),
            new HttpRequestEntityTooLarge(),
            new HttpRequestUriTooLong(),
            new HttpUnsupportedMediaType(),
            new HttpRequestedRangeNotSatisfiable(),
            new HttpExpectationFailed(),
            new HttpImATeapot(),
            new HttpUnavailableForLegalReasons(),
            new HttpInternalServerError(),
            new HttpNotImplemented(),
            new HttpBadGateway(),
            new HttpServiceUnavailable(),
            new HttpGatewayTimeout(),
            new HttpHttpVersionNotSupported(),
            new HttpVariantAlsoNegotiates(),
            new HttpInsufficientStorage(),
            new HttpLoopDetected(),
            new HttpBandwidthLimitExceeded(),
            new HttpNotExtended(),
            new HttpNetworkAuthenticationRequired(),
            new ContentTypeFilter(),
            new SizeFilter(),
        ]);

        $this->addFilters($filters);
    }

    /**
     * @param ApiClientResponse $apiClientResponse
     * @param Response          $response
     *
     * @return StatsCollectorInterface
     */
    public function filterResponse(
        ApiClientResponse $apiClientResponse,
        Response $response
    ){
        $chain          = $response->_getChain();
        $statsCollector = new StatsCollector();

        foreach($chain as $chainObject) {
            /** @var ChainObject $chainObject */
            $filter = $this->getFilter($chainObject->getObjectName());

            if(!$filter) {

                $statsCollector->error(
                    'Response filter does not exists.',
                    [
                        'chainObject' => $chainObject,
                    ]
                );

                continue;
            }

            try{

                if(!$filter->validateParams($chainObject->getParams())) {

                    $statsCollector->filterError(
                        $filter,
                        FilterInterface::ERROR_INVALID_PARAMS,
                        $chainObject->getParams()
                    );

                    continue;
                }

                $filter->setStatsCollection($statsCollector);

                $filter->call(
                    $apiClientResponse,
                    $chainObject->getParams()
                );

            } catch (\Exception $e) {

                $statsCollector->filterError(
                    $filter,
                    FilterInterface::ERROR_INTERNAL_EXCEPTION,
                    $e
                );
            }

            $filter->setStatsCollection(null);
        }

        return $statsCollector;
    }

    /**
     * @param $filterName
     *
     * @return FilterInterface|null
     */
    public function getFilter($filterName)
    {
        foreach($this->filters as $filter) {
            /** @var FilterInterface $filter */
            if($filter->getName() === $filterName) {
                return $filter;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     */
    public function addFilters(array $filters = [])
    {
        foreach($filters as $filter) {

            if(!$filter instanceof FilterInterface) {
                throw new InvalidArgumentException('Filter must be instance of FilterInterface.');
            }

            $this->filters []= $filter;
        }
    }
}