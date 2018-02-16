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

use Psr\Log\InvalidArgumentException;
use RestControl\ApiClient\ApiClientResponse;
use RestControl\TestCase\ExpressionLanguage\Expression;
use RestControl\TestCase\StatsCollector\EndContextException;
use RestControl\Utils\AbstractResponseItem;
use RestControl\Utils\Arr;
use RestControl\Validators\Factory;
use Symfony\Component\PropertyAccess\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class HasItemFilter
 *
 * @package RestControl\TestCase\ResponseFilters
 */
class HasItemFilter extends AbstractFilter implements FilterInterface
{
    use FilterTrait;

    const ERROR_INVALID_BODY = 1;
    const ERROR_INVALID_RESPONSE_ITEM_VALUE = 2;
    const ERROR_INVALID_RESPONSE_REQUIRED_VALUES = 3;
    const ERROR_INVALID_RESPONSE_STRUCTURE = 4;
    const ERROR_INVALID_RESPONSE_VALUE_TYPE = 5;
    const ERROR_INVALID_VALIDATOR = 6;

    const OPTIONAL_RESPONSE_VALUE_VALIDATOR = 'optional';
    const NOT_EMPTY_RESPONSE_VALUE_VALIDATOR = 'notEmpty';

    /**
     * @var null|\Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    protected static $accessor = null;

    /**
     * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    protected static function getAccessor()
    {
        if(!self::$accessor) {
            self::$accessor = PropertyAccess::createPropertyAccessorBuilder()
                ->getPropertyAccessor();
        }

        return self::$accessor;
    }

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
        $body                 = $this->prepareBody($apiResponse);
        $item                 = $params[0];
        $strictRequiredValues = $params[2] ?? false;
        $jsonPath             = $this->transformJsonPathToAccessor($params[1] ?? '');

        $flatStructure  = $this->processItemStructure($item->getStructure());
        $requiredValues = $item->getRequiredValues();

        $this->validFlatStructure($body, $flatStructure, $jsonPath);

        if($requiredValues === null) {
            return;
        }

        $result = Arr::containsIn(
            $requiredValues,
            $body,
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
                 $body,
                 $item->getRequiredValues()
             );
    }

    /**
     * @param array       $body
     * @param array       $flatStructure
     * @param null|string $jsonPath
     */
    protected function validFlatStructure(array $body, array $flatStructure, $jsonPath = null)
    {
        foreach($flatStructure as $path => $validatorsString) {
            $validators = $this->parseValidatorsString($validatorsString);
            $this->checkBody($body, $jsonPath . $path, $validators);
        }
    }

    /**
     * @param mixed  $body
     * @param string $path
     * @param array  $validators
     */
    protected function checkBody($body, $path, array $validators)
    {
        $accessor       = self::getAccessor();
        $statsCollector = $this->getStatsCollector();

        $statsCollector->addAssertionsCount();

        try{
            $value    = $accessor->getValue($body, $path);
        }catch (UnexpectedTypeException $e) {

            $statsCollector->filterError(
                $this,
                self::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                $body,
                [
                    'path'       => $path,
                    'validators' => $validators,
                ]
            );

            return;
        }

        if(isset($validators[self::OPTIONAL_RESPONSE_VALUE_VALIDATOR])) {

            unset($validators[self::OPTIONAL_RESPONSE_VALUE_VALIDATOR]);

            if(empty($value)) {
                return;
            }
        }

        foreach($validators as $validatorName => $validatorConfig) {

            $statsCollector->addAssertionsCount();

            try{
                if(Factory::isValid($validatorName, $value, $validatorConfig)) {
                    continue;
                }
            } catch (InvalidArgumentException $e) {

                $statsCollector->filterError(
                    $this,
                    self::ERROR_INVALID_VALIDATOR,
                    [
                        'path'            => $path,
                        'info'            => 'Invalid validator name',
                        'validatorName'   => $validatorName,
                        'value'           => $value,
                        'validatorConfig' => $validatorConfig,
                    ]
                );

                continue;
            }

            $statsCollector->filterError(
                $this,
                self::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                $value,
                [
                    'path'      => $path,
                    'validator' => $validatorName,
                    'config'    => $validatorConfig,
                ]
            );
        }
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
     * @param array  $structure
     * @param string $prefix
     *
     * @return array
     *
     * @throws EndContextException
     */
    protected function processItemStructure(array $structure, $prefix = '')
    {
        $keys = [];

        foreach($structure as $key => $value) {

            if(is_object($value)) {
                throw $this->getStatsCollector()
                           ->filterError(
                               $this,
                               self::ERROR_INVALID_RESPONSE_ITEM_VALUE,
                               $value,
                               'array|object|json'
                           )
                           ->endContext();
            }

            if(!is_array($value)) {
                $keys [$prefix . '[' . $key . ']'] = $value;
                continue;
            }

            $recKeys = $this->processItemStructure($structure[$key], '[' . $key . ']');

            foreach($recKeys as $k2ey => $v2alue) {
                $keys [$prefix . $k2ey] = $v2alue;
            }
        }

        return $keys;
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