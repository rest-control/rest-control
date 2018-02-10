<?php

/*
 * This file is part of the Rest-Control package.
 *
 * (c) Kamil Szela <kamil.szela@cothe.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RestControl\Validators;

use Psr\Log\InvalidArgumentException;

/**
 * Class Factory
 *
 * @package RestControl\Validators
 */
class Factory
{
    /**
     * @var array
     */
    protected static $validators = [
        'email'    => EmailValidatorAdapter::class,
        'date'     => DateValidatorAdapter::class,
        'host'     => HostnameValidatorAdapter::class,
        'iban'     => IBANValidatorAdapter::class,
        'ip'       => IPValidatorAdapter::class,
        'uri'      => URIValidatorAdapter::class,
        'isbn'     => ISBNValidatorAdapter::class,
        'regex'    => RegexValidatorAdapter::class,
        'length'   => LengthValidatorAdapter::class,
        'notEmpty' => NotEmptyValidatorAdapter::class,
        'uuid'     => UUIDValidatorAdapter::class,
        'string'   => StringValidatorAdapter::class,
    ];

    protected static $cache = [];

    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $options
     *
     * @return mixed
     */
    public static function isValid($name, $value, array $options = [])
    {
        $validator = self::getValidator($name);

        return $validator->isValid($value, $options);
    }

    /**
     * @param string $name
     *
     * @return AbstractValidatorAdapter
     */
    protected static function getValidator($name)
    {
        if(!isset(self::$validators[$name])) {
            throw new InvalidArgumentException('Validator ' . $name . 'not exists.');
        }

        if(isset(self::$cache[$name])) {
           return self::$cache[$name];
        }

        $validatorClass = self::$validators[$name];
        self::$cache[$name] = new  $validatorClass;

        return self::$cache[$name];
    }
}