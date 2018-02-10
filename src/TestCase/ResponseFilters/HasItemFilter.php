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

use RestControl\ApiClient\ApiClientResponse;
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
class HasItemFilter implements FilterInterface
{
    const ERROR_INVALID_BODY = 1;
    const ERROR_INVALID_RESPONSE_ITEM_VALUE = 2;
    const ERROR_INVALID_RESPONSE_REQUIRED_VALUES = 3;
    const ERROR_INVALID_RESPONSE_STRUCTURE = 4;
    const ERROR_INVALID_RESPONSE_VALUE_TYPE = 5;

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

        return !isset($params[1]) || is_string($params[1]);
    }

    /**
     * @param ApiClientResponse $apiResponse
     * @param array             $params
     *
     * @throws FilterException
     */
    public function call(ApiClientResponse $apiResponse, array $params = [])
    {
        $body = $this->prepareBody($apiResponse);
        $item = $params[0];

        $flatStructure  = $this->processItemStructure($item->getStructure());
        $requiredValues = $item->getRequiredValues();

        $this->validFlatStructure($body, $flatStructure);

        if($requiredValues !== null && !Arr::containsIn($requiredValues, $body, false)) {
            throw new FilterException(
                $this,
                self::ERROR_INVALID_RESPONSE_REQUIRED_VALUES,
                $body,
                $item->getRequiredValues()
            );
        }
    }

    /**
     * @param array $body
     * @param array $flatStructure
     */
    protected function validFlatStructure(array $body, array $flatStructure)
    {
        foreach($flatStructure as $path => $validatorsString) {
            $validators = $this->parseValidatorsString($validatorsString);
            $this->checkBody($body, $path, $validators);
        }
    }

    /**
     * @param mixed  $body
     * @param string $path
     * @param array  $validators
     *
     * @throws FilterException
     */
    protected function checkBody($body, $path, array $validators)
    {
        $accessor = self::getAccessor();

        try{
            $value    = $accessor->getValue($body, $path);
        }catch (UnexpectedTypeException $e) {
            throw new FilterException(
                $this,
                self::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                null,
                [
                    'path'       => $path,
                    'validators' => $validators,
                ]
            );
        }

        if(isset($validators[self::OPTIONAL_RESPONSE_VALUE_VALIDATOR])) {

            unset($validators[self::OPTIONAL_RESPONSE_VALUE_VALIDATOR]);

            if(empty($value)) {
                return;
            }
        }

        foreach($validators as $validatorName => $validatorConfig) {

            if(Factory::isValid($validatorName, $value, $validatorConfig)) {
               continue;
            }

            throw new FilterException(
                $this,
                self::ERROR_INVALID_RESPONSE_VALUE_TYPE,
                $value,
                [
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
     * @throws FilterException
     */
    protected function processItemStructure(array $structure, $prefix = '')
    {
        $keys = [];

        foreach($structure as $key => $value) {

            if(is_object($value)) {
                throw new FilterException(
                    $this,
                    self::ERROR_INVALID_RESPONSE_ITEM_VALUE,
                    $value,
                    'array|object|json'
                );
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
     * @throws FilterException
     */
    protected function prepareBody(ApiClientResponse $apiClientResponse)
    {
        $body = $apiClientResponse->getBody();

        if(is_array($body) || is_object($body)) {
            return $body;
        }

        $jsonBody = json_decode($body, true);

        if(is_array($jsonBody)) {
            return $jsonBody;
        }

        throw new FilterException(
            $this,
            self::ERROR_INVALID_BODY,
            $apiClientResponse->getBody(),
            'array|object|json'
        );
    }

}