<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\TestCase\ResponseFilters;

use Flow\JSONPath\JSONPath;
use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\Utils\AbstractResponseItem;
use RestControl\Utils\Arr;
use RestControl\Utils\ResponseItemsCollection;

class HasItemsFilter extends HasItemFilter
{
    const FILTER_NAME = 'hasItems';

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        if(!isset($params[0]) || !$params[0] instanceof ResponseItemsCollection) {
            return false;
        }

        return !isset($params[1]) || is_string($params[1]);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        /** @var ResponseItemsCollection $itemsCollection */
        $itemsCollection = $params[0];
        $jsonPath        = $params[1] ?? '$.*';

        $body = $this->prepareJSONBody($apiResponse, $jsonPath);

        $this->checkBodyStructure($itemsCollection, $body);
        $this->checkCollectionItems($itemsCollection, $body);
    }

    /**
     * @param ResponseItemsCollection $collection
     * @param JSONPath                $body
     */
    protected function checkBodyStructure(
        ResponseItemsCollection $collection,
        JSONPath $body
    ) {
        $itemStructure = $collection->getItemsClassStructure();

        foreach($body->data() as $i => $bodyItem) {
            $this->validStructure(
                $body,
                $itemStructure,
                '$.'.$i
            );
        }
    }

    /**
     * @param ResponseItemsCollection $collection
     * @param JSONPath                $body
     */
    protected function checkCollectionItems(
        ResponseItemsCollection $collection,
        JSONPath $body
    ) {

        foreach($collection->getItems() as $i => $itemConfig) {
            /** @var AbstractResponseItem $item */
            $item            = $itemConfig['item'];
            $requiredValues  = $item->getRequiredValues();

            if(null === $requiredValues) {
                continue;
            }

            $dataToCheck = $body->find('$.' . $i)->data()[0] ?? [];

            $result = Arr::containsIn(
                $requiredValues,
                $dataToCheck,
                $itemConfig['strictRequiredValues'],
                function($leftValue, $rightValue) {

                    if($leftValue instanceof Expression) {
                        return $this->checkExpression($rightValue, $leftValue);
                    }

                    return $leftValue === $rightValue;
                }
            );

            $this->getStatsCollector()
                 ->addAssertionsCount();

            if($result) {
                continue;
            }

            $this->getStatsCollector()
                ->filterError(
                    $this,
                    self::ERROR_INVALID_RESPONSE_REQUIRED_VALUES,
                    $dataToCheck,
                    $requiredValues
                );
        }
    }
}