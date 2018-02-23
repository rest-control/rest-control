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
use Psr\Log\InvalidArgumentException;
use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\StatsCollector\EndContextException;
use RestControl\Utils\AbstractResponseItem;
use RestControl\Utils\Arr;
use RestControl\Validators\Factory;

/**
 * Class HasItemFilter
 *
 * @package RestControl\TestCase\ResponseFilters
 */
class HasItemFilter extends AbstractFilter implements FilterInterface
{
    use FilterTrait;

    const ERROR_INVALID_BODY = 1;
    const ERROR_INVALID_RESPONSE_REQUIRED_VALUES = 3;
    const ERROR_INVALID_RESPONSE_VALUE_TYPE = 5;
    const ERROR_INVALID_VALIDATOR = 6;
    const ERROR_INVALID_RESPONSE_VALUE_STRUCTURE = 7;

    const OPTIONAL_RESPONSE_VALUE_VALIDATOR = 'optional';
    const NOT_EMPTY_RESPONSE_VALUE_VALIDATOR = 'notEmpty';

    /**
     * @return string
     */
    public function getName()
    {
        return 'hasItem';
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function validateParams(array $params = [])
    {
        if(!isset($params[0]) || !$params[0] instanceof AbstractResponseItem) {
            return false;
        }

        if(!empty($params[1]) && !is_string($params[1])) {
            return false;
        }

        return !isset($params[2]) || is_bool($params[2]);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $item                 = $params[0];
        $strictRequiredValues = $params[2] ?? false;

        $body = $this->prepareJSONSingleItemBody($apiResponse, $item, $params[1] ?? '$');

        if(!$body) {
            return;
        }

        $itemStructure  = $item->getStructure();
        $requiredValues = $item->getRequiredValues();

        $this->validStructure($body, $itemStructure);

        if($requiredValues === null) {
            return;
        }

        $result = Arr::containsIn(
            $requiredValues,
            $body->data(),
            $strictRequiredValues,
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
           return;
        }

        $this->getStatsCollector()
             ->filterError(
                 $this,
                 self::ERROR_INVALID_RESPONSE_REQUIRED_VALUES,
                 $body->data(),
                 $item->getRequiredValues()
             );
    }

    /**
     * @param ApiClientResponse    $apiResponse
     * @param AbstractResponseItem $item
     * @param string               $path
     *
     * @return JSONPath|null
     */
    protected function prepareJSONSingleItemBody(ApiClientResponse $apiResponse, AbstractResponseItem $item, $path = '$')
    {
        $body = $this->prepareJSONBody($apiResponse, $path);

        if(count($body->data()) > 1) {
            $this->getStatsCollector()
                ->filterError(
                    $this,
                    self::ERROR_INVALID_RESPONSE_VALUE_STRUCTURE,
                    $body->data(),
                    $item->getStructure()
                );

            return null;
        }

        return new JSONPath($body->data()[0] ?? []);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param string            $path
     *
     * @return JSONPath
     */
    protected function prepareJSONBody(ApiClientResponse $apiResponse, $path = '$')
    {
        $preparedBody = $this->prepareBody($apiResponse);
        $path         = $path ?? '$';

        if('$' === $path) {
            $path .= '.';
        }

        return (new JSONPath($preparedBody))->find($path);
    }

    /*
     *
     * @param JSONPath $body
     * @param array    $itemSegmentStructure
     * @param string   $trace
     */
    protected function validStructure(JSONPath $body, array $itemSegmentStructure, $trace = '$')
    {
        foreach($itemSegmentStructure as $index => $segmentData) {

            $indexTrace = $trace . '.' . $this->removeSystemSignatures($index);
            $data       = $body->find($indexTrace)->data()[0] ?? [];

            $isRepeatSignature  = $this->isRepeatSignature($index);
            $isArraySegmentData = is_array($segmentData);

            if(!$isRepeatSignature && $isArraySegmentData) {

                $this->checkArraySegmentData(
                    $body,
                    $data,
                    $segmentData,
                    $indexTrace
                );

                continue;

            } else if($isRepeatSignature && $isArraySegmentData) {

                $this->checkRepeatedArraySegmentData(
                    $body,
                    $data,
                    $segmentData,
                    $indexTrace
                );

                continue;

            } else if($isRepeatSignature) {

                $this->checkRepeatedSegmentData(
                    $body,
                    $data,
                    $segmentData,
                    $indexTrace
                );

                continue;
            }

            $this->validateSegmentData($body, $segmentData, $indexTrace);
        }
    }

    /**
     * @param $body
     * @param $data
     * @param $segmentData
     * @param $indexTrace
     */
    protected function checkRepeatedSegmentData($body, $data, $segmentData, $indexTrace)
    {
        if($data && !is_array($data)) {
            $this->getStatsCollector()->filterError(
                $this,
                self::ERROR_INVALID_RESPONSE_VALUE_STRUCTURE,
                $data,
                [
                    'path'       => $indexTrace,
                    'validators' => [
                        'array' => []
                    ],
                ]
            );

            return;
        }

        foreach((array)$data as $iterationIndex => $iterationData) {
            $this->validateSegmentData($body, $segmentData, $indexTrace . '.'. $iterationIndex);
        }
    }

    /**
     * @param $body
     * @param $data
     * @param $segmentData
     * @param $indexTrace
     */
    protected function checkRepeatedArraySegmentData($body, $data, $segmentData, $indexTrace)
    {
        if($data && !is_array($data)) {
            $this->getStatsCollector()->filterError(
                $this,
                self::ERROR_INVALID_RESPONSE_VALUE_STRUCTURE,
                $data,
                [
                    'path'       => $indexTrace,
                    'validators' => [
                        'array' => []
                    ],
                ]
            );

            return;
        }

        foreach((array)$data as $iterationIndex => $iterationData) {
            $this->validStructure($body, $segmentData, $indexTrace . '.' . $iterationIndex);
        }
    }

    /**
     * @param $body
     * @param $data
     * @param $segmentData
     * @param $indexTrace
     */
    protected function checkArraySegmentData($body, $data, $segmentData, $indexTrace)
    {
        if($data && !is_array($data)) {
            $this->getStatsCollector()->filterError(
                $this,
                self::ERROR_INVALID_RESPONSE_VALUE_STRUCTURE,
                $data,
                [
                    'path'       => $indexTrace,
                    'validators' => [
                        'array' => []
                    ],
                ]
            );

            return;
        }

        $this->validStructure($body, $segmentData, $indexTrace);
    }

    /**
     * @param JSONPath $body
     * @param string   $validatorsString
     * @param string   $trace
     */
    protected function validateSegmentData(JSONPath $body, $validatorsString, $trace)
    {
        $data           = $body->find($trace)->data()[0] ?? [];
        $validators     = $this->parseValidatorsString($validatorsString);
        $statsCollector = $this->getStatsCollector();

        if($this->checkOptionalValidator($data, $validators)) {
            return;
        }

        foreach($validators as $validatorName => $validatorConfig) {

            $statsCollector->addAssertionsCount();

            try{
                if(Factory::isValid($validatorName, $data, $validatorConfig)) {
                    continue;
                }
            } catch (InvalidArgumentException $e) {

                $statsCollector->filterError(
                    $this,
                    self::ERROR_INVALID_VALIDATOR,
                    [
                        'path'            => $trace,
                        'info'            => 'Invalid validator name',
                        'validatorName'   => $validatorName,
                        'value'           => $data,
                        'validatorConfig' => $validatorConfig,
                    ]
                );

                continue;
            }

            $statsCollector->filterError(
                $this,
                self::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                $data,
                [
                    'path'      => $trace,
                    'validator' => $validatorName,
                    'config'    => $validatorConfig,
                ]
            );
        }
    }

    /**
     * Returns true if $data is empty.
     *
     * @param mixed $data
     * @param array $validators
     *
     * @return bool
     */
    protected function checkOptionalValidator($data, array &$validators)
    {
        if(!isset($validators[self::OPTIONAL_RESPONSE_VALUE_VALIDATOR])) {
            return false;
        }

        $this->getStatsCollector()
             ->addAssertionsCount();

        unset($validators[self::OPTIONAL_RESPONSE_VALUE_VALIDATOR]);

        return empty($data);
    }

    /**
     * @param string $index
     *
     * @return bool
     */
    protected function isRepeatSignature($index)
    {
        $repeatChar = '.*';
        $end        = substr($index, -strlen($repeatChar));

        return $end === $repeatChar;
    }

    /**
     * @param string $index
     *
     * @return bool|string
     */
    protected function removeSystemSignatures($index)
    {
        if(!$this->isRepeatSignature($index)) {
            return $index;
        }

        return substr($index, 0, strlen($index) - 2);
    }

    /**
     * @param $validatorsString
     *
     * @return array
     */
    protected function parseValidatorsString($validatorsString)
    {
        $validatorsString = trim($validatorsString);

        if(!$validatorsString) {
            return [];
        }

        $configuration = [];
        $validators = explode('|', $validatorsString);

        foreach($validators as $validator) {
            $parts = explode(':', $validator);
            $configuration[trim($parts[0])] = isset($parts[1])
                ? explode(',', trim($parts[1])) : [];
        }

        //default, all indexes all required.
        if(!isset($configuration[self::OPTIONAL_RESPONSE_VALUE_VALIDATOR])) {
            $configuration[self::NOT_EMPTY_RESPONSE_VALUE_VALIDATOR] = [];
        }

        return $configuration;
    }

    /**
     * @param ApiClientResponse $apiClientResponse
     *
     * @return array|object
     *
     * @throws EndContextException
     */
    protected function prepareBody(ApiClientResponse $apiClientResponse)
    {
        $body = $apiClientResponse->getBody();

        $this->getStatsCollector()
             ->addAssertionsCount();

        if(is_array($body) || is_object($body)) {
            return $body;
        }

        $jsonBody = json_decode($body, true);

        if(is_array($jsonBody)) {
            return $jsonBody;
        }

        throw $this->getStatsCollector()
                   ->filterError(
                       $this,
                       self::ERROR_INVALID_BODY,
                       $apiClientResponse->getBody(),
                       'array|object|json'
                   )
                   ->endContext();
    }
}