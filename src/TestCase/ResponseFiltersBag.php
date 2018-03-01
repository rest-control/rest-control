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
use RestControl\TestCase\ResponseFilters\FilterInterface;
use Psr\Log\InvalidArgumentException;
use RestControl\TestCase\ResponseFilters\HasItemFilter;
use RestControl\TestCase\ResponseFilters\HasItemsFilter;
use RestControl\TestCase\ResponseFilters\HeaderFilter;
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
use RestControl\TestCase\ResponseFilters\JsonFilter;
use RestControl\TestCase\ResponseFilters\JsonPathFilter;
use RestControl\TestCase\StatsCollector\StatsCollector;
use RestControl\TestCase\StatsCollector\StatsCollectorInterface;

/**
 * Class ResponseFiltersBag
 *
 * @package RestControl\TestCase
 */
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